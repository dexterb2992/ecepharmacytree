<?php  
namespace ECEPharmacyTree\Repositories;
use Input; 
use Redirect;
use Auth;
use \stdClass;

use Illuminate\Http\Request;

use ECEPharmacyTree\Http\Requests;
use ECEPharmacyTree\Http\Controllers\Controller;

use ECEPharmacyTree\StockReturnCode;
use ECEPharmacyTree\StockReturn;
use ECEPharmacyTree\OrderLotNumber;
use ECEPharmacyTree\Order;
use ECEPharmacyTree\OrderDetail;
use ECEPharmacyTree\ProductStockReturn;
use ECEPharmacyTree\Inventory;
use ECEPharmacyTree\Product;
use ECEPharmacyTree\Log;


class StockReturnRepository {

    public function replace($input){
        $ProductStockReturn = ProductStockReturn::find($input['product_stock_return_id']);

        if( !isset($ProductStockReturn->id) )
            return json_encode( array(
                "status" => 500,
                "error" => "We can't find exactly what you are looking for. Please refresh the page and try again."
            ) );

        $max_replaceable = $ProductStockReturn->quantity;

        // get total replacement
        $total_replacements = 0;
        $old_replacements = json_decode($ProductStockReturn->replacement);
        // dd($old_replacements);
        foreach ($old_replacements as $r) {
            $total_replacements+=  $r->quantity;
        }

        $inventories = [];
        $remaining_replaceable = $max_replaceable - $total_replacements;

        if( is_array($input["inventory_ids"]) ){
            foreach ($input["inventory_ids"] as $key => $value) {
                $inventory = Inventory::find($value);
                $inventories[] = $inventory;
            }
            // dd($inventories);
            usort($inventories, "cmp_available_quantity"); // this will sort the array to ascending
            // dd($inventories);
        }else{
            $inventory = Inventory::find($input["inventory_ids"]);
            array_push($inventories, $inventory);
        }

        $replacements = [];
        // $total_replacements = 0;

        foreach ($inventories as $inventory) {
            // $qty = $inventory
            $qty_replaced = 0;
            if( $remaining_replaceable > 0 ){
                $old_avq = $inventory->available_quantity;

                if( $inventory->available_quantity <= $remaining_replaceable ){
                    $remaining_replaceable-= $inventory->available_quantity;
                    $qty_replaced = $inventory->available_quantity;

                    $inventory->available_quantity = 0;

                }else{
                    $inventory->available_quantity -= $remaining_replaceable;
                    $qty_replaced = $remaining_replaceable;
                }

                if( $inventory->save() ){
                    $log = "Replaced $qty_replaced "
                            .str_auto_plural($ProductStockReturn->product->packing, $qty_replaced)
                            ." of {$ProductStockReturn->product->name} from <a href='".route('Inventory::index')."?q=$inventory->lot_number'>
                                Lot# $inventory->lot_number </a>. Available quantity has changed from $old_avq to $inventory->available_quantity.
                            Reference <a href='".route('orders')."?q=#{$ProductStockReturn->stock_return->order->id}' target='_blank'>
                                Order# {$ProductStockReturn->stock_return->order->id}
                            </a>";
                            

                    Log::create([
                        'user_id' => Auth::user()->id,
                        'action'  => $log,
                        'table' => 'inventories',
                        'branch_id' => session()->get('selected_branch')
                    ]);

                    array_push($replacements, array("inventory_id" => $inventory->id, "quantity" => $qty_replaced));
                    $total_replacements+= $qty_replaced;
                }
            }
            
        }
        
        // pre($old_replacements);
                
        // filter for duplicates
        foreach ($old_replacements as $i => $o) {
            foreach ($replacements as $j => $r) {
                if( $o->inventory_id == $r["inventory_id"] ){
                    // remove duplicate & update the quantity
                    $replacements[$j]["quantity"] = $o->quantity + $r["quantity"];
                    unset($old_replacements->$i);
                }
            }
        }

        //combine old & new replacements
        foreach ($old_replacements as $key => $r) {
            $replacements[] = ['inventory_id' => $r->inventory_id, 'quantity' => $r->quantity];
        }

        $ProductStockReturn->replacement = json_encode($replacements);
        $max_replaceable = $ProductStockReturn->quantity - $total_replacements;

        if( $ProductStockReturn->save() ){
            $p_packing = $ProductStockReturn->inventory->product->packing;
            $msg_status = "($ProductStockReturn->quantity "
                .str_auto_plural($p_packing, $ProductStockReturn->quantity)
                ." returned, $total_replacements "
                .str_auto_plural($p_packing, $total_replacements)." replaced, $ProductStockReturn->defective_quantity "
                .str_auto_plural($p_packing, $ProductStockReturn->defective_quantity)." marked as defective)";

            return json_encode( array(
                "status" => 200, 
                "max_replaceable" => $max_replaceable, 
                "replaced_qty" => $total_replacements,
                "returned_qty" => $ProductStockReturn->quantity,
                "msg_status" => $msg_status
                )
            );
        }

        // todo tomorrow: mag replace sya, pero, huroton niya ang sa lot number nga quantity bisan gamay ra ang max
        //          replaceable. Fix this asap.
    }

}
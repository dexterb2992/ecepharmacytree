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
            $__q = 0;
            if( is_object($r) ){
                $__q = $r->quantity;
            }else if( is_array($r) ){
                $__q = $r['quantity'];
            }

            $total_replacements+=  $__q;
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
                            Reference <a href='".url("orders/{$ProductStockReturn->stock_return->order->id}")."' target='_blank'>
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
            $_old_r = 0;
            if( is_array($o) ){
                $_old_r = $o->inventory_id;
            }else if( is_object($o) ){
                $_old_r = $o['inventory_id'];
            }

            foreach ($replacements as $j => $r) {
                $__r = 0;
                if( is_array($r) ){
                    $__r = $r->inventory_id;
                }else if( is_object($o) ){
                    $__r = $r['inventory_id'];
                }
                if( $_old_r == $__r ){
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
    }

    public function update_defective_stocks($input){
        $def_qty = $input['defective_quantity'];

        $psr = ProductStockReturn::find($input['id']);
        $psr->defective_quantity += $def_qty;
        $psr->load('stock_return');
        $psr->load('product');
        $psr->stock_return->load('inventory');

        if( $psr->save() ){
            // $psr->stock_return->inventory->available_quantity -= $def_qty;
            $inventory = Inventory::find($psr->inventory->id);
            $old_avq = $inventory->available_quantity;
            $inventory->available_quantity -= $def_qty;
            if( $inventory->save() ){
                $activity_log = "$def_qty ".str_auto_plural($inventory->product->packing, $def_qty)
                                ." of {$inventory->product->name} from inventory with  
                                <a href='".route('Inventory::index')."?q=$inventory->lot_number' target='_blank'>Lot# $inventory->lot_number</a>
                                from <a href='".url("orders/{$psr->stock_return->order_id}")."' target='_blank'>Order# {$psr->stock_return->order_id}</a> <br/> Available quantity changed from $old_avq to $inventory->available_quantity.";

                Log::create([
                    'user_id' => Auth::user()->id,
                    'action'  => "Removed $activity_log",
                    'table' => 'inventories',
                    'branch_id' => session()->get('selected_branch')
                ]);

                $total_replacements = 0;
                foreach (json_decode($psr->replacement) as $key => $replacement) {
                    $increment = 0;
                    if( is_object($replacement) ) 
                        $increment = $replacement->quantity;
                    else if( is_array($replacement) ) 
                        $increment = $replacement['quantity'];
                    $total_replacements+= $increment;
                }
                
                $max_replaceable = $psr->quantity - $total_replacements;
                $p_packing = $inventory->product->packing;
                $msg_status = "($psr->quantity "
                    .str_auto_plural($p_packing, $psr->quantity)
                    ." returned, $total_replacements "
                    .str_auto_plural($p_packing, $total_replacements)." replaced, $psr->defective_quantity "
                    .str_auto_plural($p_packing, $psr->defective_quantity)." marked as defective)";

                $psr->msg_status = $msg_status;
                $psr->max_replaceable = $max_replaceable;
                $psr->max_removable = $psr->quantity - $psr->defective_quantity;

                return json_encode( ['status' => 200, 'msg' => "Success", "data" => $psr] );
            }
            
        }

        return json_encode(["status" => 500, "error" => "Sorry. we can't process your request right now."]);
    }

    public function show_all_returned_products($id){
        $stock_return = StockReturn::find($id);

        if( !isset($stock_return->id) )
            return json_encode( array("error" => "Not found", "status" => 500) );

        $products_returned = $stock_return->product_stock_returns;
        foreach ($products_returned as $pr) {
            $pr->load('product');

            $__r = json_decode( $pr->replacement );
            if(  is_object( $__r ) ){
                $pr->replacement = array_values( get_object_vars( $__r ) );
            }else if( is_array( $__r ) ){
                $pr->replacement = array_values($__r);
            }
            

            $inventory = Inventory::find($pr->inventory->id);
            $p_packing = $inventory->product->packing;

            $total_replacements = 0;
            foreach ($pr->replacement as $key => $replacement) {
                $increment = 0;
                if( is_object($replacement) ) 
                    $increment = $replacement->quantity;
                else if( is_array($replacement) ) 
                    $increment = $replacement['quantity'];
                $total_replacements+= $increment;
            }
            $msg_status = "($pr->quantity "
                .str_auto_plural($p_packing, $pr->quantity)
                ." returned, $total_replacements "
                .str_auto_plural($p_packing, $total_replacements)." replaced, $pr->defective_quantity "
                .str_auto_plural($p_packing, $pr->defective_quantity)." marked as defective)";

            $pr->total_replacement = $total_replacements;
            $pr->msg_status = $msg_status;
            $pr->max_replaceable = $pr->quantity - $total_replacements;
        }
        return $products_returned;
    }

    public function store($input){

        $stock_return = new StockReturn;

        $stock_return->order_id = $input["order_id"];
        $stock_return->return_code = $input["return_code"];
        $stock_return->brief_explanation = $input["brief_explanation"];
        $stock_return->action = $input["action"];
        $stock_return->all_product_is_returned = $input['all_product_is_returned'];
        $stock_return->amount_refunded = $input['amount_refunded'];

        // dd($stock_return);

        $order = Order::find($input["order_id"]);
        if( $order->billing->payment_status != 'paid' ){
           
            return [
                'status' => 'error',
                'flash_message' => [
                    'msg' => "Sorry, but order #$order->id is not yet paid. You can't return an unpaid order.",
                    'type' => 'error'
                ]
            ];

        }

        if( count($order->lot_numbers) < 1 ){
            return [
                'status' => 'error',
                'flash_message' => [
                    'msg' => "Sorry, but it seems Order #$order->id has not fulfill any item from its details yet.",
                    'type' => 'error'
                ]
            ];
        }

        $lesser_lot_number = [];
        $temp_lot_number = [];

        $activity_log = [];

        foreach ($order->lot_numbers as $lot_number) {
            $lot_number->load('inventory');
        }

        $arr_lot_numbers = $order->lot_numbers->toArray();
        // pre($arr_lot_numbers);
        for($x = count($arr_lot_numbers)-1; $x >= 0; $x--){
            //x = 1, x-1 = 0
            if( isset($arr_lot_numbers[$x-1]) ){
                if( $arr_lot_numbers[$x-1]['inventory']['available_quantity'] >  $arr_lot_numbers[$x]['inventory']['available_quantity'] ){
                    $temp = $arr_lot_numbers[$x];
                    // $lesser_lot_number = $arr_lot_numbers[$x-1];
                    $arr_lot_numbers[$x] = $arr_lot_numbers[$x-1];
                    $arr_lot_numbers[$x-1] = $temp;
                }
            }
        }
        // pre($arr_lot_numbers);
        // dd($input['all_product_is_returned']);

        if( $input['all_product_is_returned'] == 1 ){
            foreach ($arr_lot_numbers as $lot_number) {
                // $input['products_return_qtys'] = [$lot_number['inventory']['product_id'] => $lot_number['quantity']];
                $input['products_return_qtys'][ $lot_number['inventory']['product_id'] ] = $lot_number['quantity'];

            }
        }

        // dd($input['products_return_qtys'] );

        if( !isset($input['products_return_qtys']) && $input['all_product_is_returned'] != 1 ){
            
            return [
                'status' => 'error',
                'flash_message' => [
                    'msg' => 'Sorry, some things are missing when you submit your request. Please try again.',
                    'type' => 'error'
                ]

            ];
        }

        $stock_return->save();

        // let's check if all the products' returned is exactly equal to the quantity in order details
        // (this is when the admin mistakenly chosen the 'Not all products on this order are returned' radio button)
        $order->load('order_details');

        

        // continue returning items to inventory
        if( isset($input["products_return_qtys"]) ){
            foreach($input['products_return_qtys'] as $key_product_id => $input_value){
                
                $product = Product::findOrFail($key_product_id);

                $input['products_return_qtys']['remaining'] = [$key_product_id => $input_value]; // iv: 4, lq: 5(1 & 4)

                // loop through all lot numbers associated on this order
                foreach ($arr_lot_numbers as $lot_number) {
                    // $lot_number[quantity] = 1 , remaining = 3
                    if($lot_number['inventory']['product_id'] == $key_product_id){ // IF FOUND
                        $inventory = Inventory::find($lot_number['inventory']['id']);

                        if( $input['products_return_qtys']['remaining'][$key_product_id] > $lot_number['quantity'] ){
                            $input['products_return_qtys']['remaining'][$key_product_id] -= $lot_number['quantity'];
                            $returned_qty = $lot_number['quantity']; 
                        }else if( $input['products_return_qtys']['remaining'][$key_product_id] == $lot_number['quantity'] ){
                            $input['products_return_qtys']['remaining'][$key_product_id] = 0;
                            $returned_qty = $input_value;
                        }else if( $input['products_return_qtys']['remaining'][$key_product_id] < $lot_number['quantity'] ){
                            $returned_qty = $input['products_return_qtys']['remaining'][$key_product_id];
                        }

                        // $inventory->available_quantity = $inventory->available_quantity + $returned_qty;
                        

                        // save the flags to order_details
                        $order_detail = OrderDetail::where('order_id', '=', $order->id)
                            ->where('product_id', '=', $inventory->product_id )->where('quantity', '>', 'quantity_returned')->first();
                        // $order_detail->quantity_returned+= $returned_qty;
                        
                        if( $order_detail->quantity_returned < $order_detail->quantity ){
                            if( $input['all_product_is_returned'] == 1 ){
                                $input_value = $returned_qty = $order_detail->quantity - $order_detail->quantity_returned;

                            }

                            $inventory->available_quantity = $inventory->available_quantity + $returned_qty;
                            $order_detail->quantity_returned+= $returned_qty;

                            // save the inventory ID
                            $sr_detail = new ProductStockReturn;
                            $sr_detail->product_id = $key_product_id;
                            $sr_detail->stock_return_id = $stock_return->id;
                            $sr_detail->quantity = $input_value;

                            $sr_detail->inventory_id = $inventory->id;
                            // set default value for replacement
                            $sr_detail->replacement = json_encode( [array("inventory_id" => 0, "quantity" => 0)] );

                            $activity_log[] ="$input_value ".str_auto_plural($inventory->product->packing, $input_value)
                                        ." to <a href='".route('Inventory::index')."?q=$inventory->lot_number' 
                                        target='_blank'>Lot# $inventory->lot_number </a> ";

                            $sr_detail->save();
                        }
                        //  proceed
                        $order_detail->save();
                        $inventory->save();

                    }
                }

                // $sr_detail->save();
            }
        }

        $activity_log = implode(", \n", $activity_log)."from Order#$order->id";


        if( $input['all_product_is_returned'] == 1  ){
            $order->is_returned = 1;
            $order->save();
            
        }else{
            // $check = $order->order_details->where('quantity_returned', '<', 'quantity')->count();
            $order = Order::find($input["order_id"]);  // to override the cached collection
            $details = $order->order_details;
            $has_remaining_returnable = false;

            foreach ($details as $detail) {
                // pre($detail->quantity." > ".$detail->quantity_returned);
                if( $detail->quantity > $detail->quantity_returned ){
                    $has_remaining_returnable = true;
                }
            }
            
            if( $has_remaining_returnable == false ){
                $order->is_returned = 1;
                $order->save();
            }

        }

        if( $stock_return->save() ){
            Log::create([
                'user_id' => Auth::user()->id,
                'action'  => "Returned $activity_log",
                'table' => 'inventories',
                'branch_id' => session()->get('selected_branch')
            ]);

            return [
                'status' => 'success',
                'flash_message' => [
                    'type' => 'info', 
                    'msg' => 'A stock has been successfully returned.'
                ]

            ];


        }

        return [
            'status' => 'error',
            'flash_message' => [
                'type' => 'error', 
                'msg' => 'Sorry, we can\'t process your request right now. Please try again later.'
            ]
        ];
    }

}
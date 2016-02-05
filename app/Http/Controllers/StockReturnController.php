<?php

namespace ECEPharmacyTree\Http\Controllers;
use Input; 
use Redirect;
use Auth;

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

class StockReturnController extends Controller
{


    public function store(){
        $input = Input::all();
        // dd($input);
        if( !isset($input["order_id"]) )
            return Redirect::back()->withInput()->withFlash_message([
                'msg' => 'Sorry, some things are missing when you submit your request. Please try again.',
                'type' => 'error'
            ]);

        $stock_return = new StockReturn;

        $stock_return->order_id = $input["order_id"];
        $stock_return->return_code = $input["return_code"];
        $stock_return->brief_explanation = $input["brief_explanation"];
        $stock_return->action = $input["action"];
        $stock_return->all_product_is_returned = $input['all_product_is_returned'];
        $stock_return->amount_refunded = $input['amount_refunded'];

        $order = Order::find($input["order_id"]);
        if( $order->billing->payment_status != 'paid' ){
            return Redirect::back()->withInput()->withFlash_message([
                'msg' => "Sorry, but order #$order->id is not yet paid. You can't return an unpaid order.",
                'type' => 'error'
            ]);
        }

        $lesser_lot_number = [];
        $temp_lot_number = [];

        $activity_log = [];

        foreach ($order->lot_numbers as $lot_number) {
            $lot_number->load('inventory');
            // if( $input['all_product_is_returned'] == 1 ){
            //     $activity_log[] = "$lot_number->quantity ".str_auto_plural($lot_number->inventory->product->packing,$lot_number->quantity)
            //         ." to<a href='".route('Inventory::index')."?q={$lot_number->inventory->lot_number}' 
            //         target='_blank'>Lot# {$lot_number->inventory->lot_number} </a> ";

            // }
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
                }else{
                    // $lesser_lot_number = $arr_lot_numbers[$x];
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
            return Redirect::back()->withInput()->withFlash_message([
                'msg' => 'Sorry, some things are missing when you submit your request. Please try again.',
                'type' => 'error'
            ]);
        }

        $stock_return->save();

        // let's check if all the products' returned is exactly equal to the quantity in order details
        // (this is when the admin mistakenly chosen the 'Not all products on this order are returned' radio button)
        $order->load('order_details');

        

        // continue returning items to inventory
        if( isset($input["products_return_qtys"]) ){
            foreach($input['products_return_qtys'] as $key_product_id => $input_value){
                // $sr_detail = new ProductStockReturn;
                // $sr_detail->product_id = $key_product_id;
                // $sr_detail->stock_return_id = $stock_return->id;
                // $sr_detail->quantity = $input_value;
                
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
                            ->where('product_id', '=', $inventory->product_id )->first();
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

                            $activity_log[] ="$input_value ".str_auto_plural($inventory->product->packing, $input_value)
                                        ." to <a href='".route('Inventory::index')."?q=$inventory->lot_number' 
                                        target='_blank'>Lot# $inventory->lot_number </a> ";

                            $sr_detail->save();
                        }
                        //  proceed
                        $order_detail->save();
                        $inventory->save();

                        /*// save the inventory ID
                        $sr_detail->inventory_id = $inventory->id;

                        $activity_log[] ="$input_value ".str_auto_plural($inventory->product->packing, $input_value)
                                    ." to <a href='".route('Inventory::index')."?q=$inventory->lot_number' 
                                    target='_blank'>Lot# $inventory->lot_number </a> ";*/

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
                pre($detail->quantity." > ".$detail->quantity_returned);
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

            return redirect('inventory/all')->withFlash_message([
                'type' => 'success', 'msg' => 'A stock has been successfully returned.'
            ]);
        }

        return redirect('inventory/all')->withFlash_message([
            'type' => 'error', 'msg' => 'Sorry, we can\'t process your request right now. Please try again later.'
        ]);

    }


    public function stock_return_codes(){
        $codes = StockReturnCode::all();
        return $codes;
    }

    public function show_all_returned_products($id){
        $stock_return = StockReturn::find($id);

        if( !isset($stock_return->id) )
            return json_encode( array("error" => "Not found", "status" => 500) );

        $products_returned = $stock_return->product_stock_returns;
        foreach ($products_returned as $pr) {
            $pr->load('product');
        }
        return $products_returned;
    }

    public function udate_defective_stocks(){
        $input = Input::all();
        $def_qty = $input['defective_quantity'];

        $psr = ProductStockReturn::find($input['id']);
        $psr->defective_quantity += $def_qty;
        $psr->load('stock_return');
        $psr->load('product');
        $psr->stock_return->load('inventory');

        if( $psr->save() ){
            // $psr->stock_return->inventory->available_quantity -= $def_qty;
            $inventory = Inventory::find($psr->inventory->id);
            $inventory->available_quantity -= $def_qty;
            if( $inventory->save() ){
                $activity_log = "$def_qty ".str_auto_plural($inventory->product->packing, $def_qty)
                                ." of {$inventory->product->name} from inventory with  
                                <a href='".route('Inventory::index')."?q=$inventory->lot_number' target='_blank'>Lot# $inventory->lot_number</a>
                                from Order# {$psr->stock_return->order_id}";

                Log::create([
                    'user_id' => Auth::user()->id,
                    'action'  => "Removed $activity_log",
                    'table' => 'inventories',
                    'branch_id' => session()->get('selected_branch')
                ]);
                return json_encode( ['status' => 200, 'msg' => "Success", "data" => $psr] );
            }
            
        }

        return json_encode(["status" => 500, "error" => "Sorry. we can't process your request right now."]);
    }

}

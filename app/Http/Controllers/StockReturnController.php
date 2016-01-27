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

        $activity_log = "Returned ";

        foreach ($order->lot_numbers as $lot_number) {
            $lot_number->load('inventory');

            if( $input['all_product_is_returned'] == 1 ){
                $activity_log.= "$lot_number->quantity {$lot_number->inventory->product->packing} to 
                        <a href='".route('Inventory::index')."?q={$lot_number->inventory->lot_number}' 
                                target='_blank'>Lot# {$lot_number->inventory->lot_number} </a>\n ";
            }

        }

        $arr_lot_numbers = $order->lot_numbers->toArray();

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

        if( $input['all_product_is_returned'] == 1 ){
            foreach ($arr_lot_numbers as $lot_number) {
                $input['products_return_qtys'] = [$lot_number['inventory']['product_id'] => $lot_number['quantity']];
            }
        }

        if( !isset($input['products_return_qtys']) ){
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
            foreach($input['products_return_qtys'] as $key_product_id => $input_value){
                $sr_detail = new ProductStockReturn;
                $sr_detail->product_id = $key_product_id;
                $sr_detail->stock_return_id = $stock_return->id;
                $sr_detail->quantity = $input_value;
                
                $product = Product::findOrFail($key_product_id);

                $input['products_return_qtys']['remaining'] = [$key_product_id => $input_value]; // iv: 4, lq: 5(1 & 4)

                foreach ($arr_lot_numbers as $lot_number) {
                    // $lot_number[quantity] = 1 , remaining = 3
                    if($lot_number['inventory']['product_id'] == $key_product_id){
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

                        $inventory->available_quantity = $inventory->available_quantity + $returned_qty;
                        $inventory->save();
                        $activity_log.= "$input_value $product->packing to <a href='".route('Inventory::index')."?q=$inventory->lot_number' 
                                    target='_blank'>Lot# $inventory->lot_number </a>\n ";
                    }
                }

                $sr_detail->save();
            }

        if( $input['all_product_is_returned'] == 1  ){
            $order->is_returned = 1;
            $order->save();
            
        }

        if( $stock_return->save() ){
            Log::create([
                'user_id' => Auth::user()->id,
                'action'  => $activity_log,
                'table' => 'inventories'
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

}

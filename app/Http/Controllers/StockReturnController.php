<?php

namespace ECEPharmacyTree\Http\Controllers;
use Input; 
use Redirect;

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

class StockReturnController extends Controller
{

    public function index(){
        //
    }

    public function create(){
        //
    }


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

        // temporary save lang sa 
        // pre($input);

        $stock_return->save();

        $order = Order::find($input["order_id"]);



        

        $lesser_lot_number = [];
        $temp_lot_number = [];
        // dd($lesser_lot_number);
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
                }else{
                    // $lesser_lot_number = $arr_lot_numbers[$x];
                }
            }
        }

        // pre($arr_lot_numbers);
        pre($input);
        if( $input['all_product_is_returned'] == 1 ){
            foreach ($arr_lot_numbers as $lot_number) {
                $input['products_return_qtys'] = [$lot_number['inventory']['product_id'] => $lot_number['quantity']];
            }
        }
        // pre('products_return_qtys: ');
        // pre($input['products_return_qtys']);

        foreach($input['products_return_qtys'] as $key_product_id => $input_value){
            // $order_detail = $order->order_details()->where('product_id', $key);
            $sr_detail = new ProductStockReturn;
            $sr_detail->product_id = $key_product_id;
            $sr_detail->stock_return_id = $stock_return->id;
            $sr_detail->quantity = $input_value;
            

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
                }
            }

            $sr_detail->save();
        }

        if( $input['all_product_is_returned'] == 1  ){
            $order->status = 'refunded_in_full';
            $order->save();
        }

        if( $stock_return->save() )
            return redirect('inventory/all')->withFlash_message([
                'type' => 'success', 'msg' => 'A stock has been successfully returned.'
            ]);

        return redirect('inventory/all')->withFlash_message([
            'type' => 'error', 'msg' => 'Sorry, we can\'t process your request right now. Please try again later.'
        ]);

    }

    public function show($id){
        //
    }

    public function stock_return_codes(){
        $codes = StockReturnCode::all();
        return $codes;
    }

    public function edit($id){
        //
    }

    public function update(Request $request, $id){
        //
    }

    public function destroy($id){
        //
    }
}

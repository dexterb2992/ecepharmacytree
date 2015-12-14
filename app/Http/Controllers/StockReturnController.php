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
        $stock_return = new StockReturn;

        $return_quantity = $input["return_quantity"];

        $stock_return->order_id = $input["order_id"];
        $stock_return->return_product_id = $input["return_product_id"];
        $stock_return->return_quantity = $return_quantity;
        $stock_return->return_code = $input["return_code"];
        $stock_return->brief_explanation = $input["brief_explanation"];
        $stock_return->action = $input["action"];
        $stock_return->exchange_product_id = $input["exchange_product_id"];

        $order = Order::find($input["order_id"]);

        $lesser_lot_number = [];
        $temp_lot_number = [];
        // dd($lesser_lot_number);
        foreach ($order->lot_numbers as $lot_number) {
            $lot_number->load('inventory');
        }

        $arr_lot_numbers = $order->lot_numbers->toArray();

        // dd($arr_lot_numbers);

        for($x = count($arr_lot_numbers)-1; $x >= 0; $x--){
            //x = 1, x-1 = 0
            if( isset($arr_lot_numbers[$x-1]) ){
                if( $arr_lot_numbers[$x-1]['inventory']['available_quantity'] <  $arr_lot_numbers[$x]['inventory']['available_quantity'] ){
                    $lesser_lot_number = $arr_lot_numbers[$x-1];
                }else{
                    $lesser_lot_number = $arr_lot_numbers[$x];
                }
            }
        }
        
        // dd($lesser_lot_number);
        dd($order->lot_numbers->where('id', 2)); // for tomorrow: 
        // actual order qty: 5
        // if quantity_received is 150, and avaible_qty is 145
        // and stock returned is 4
        // and we have 2 lot numbers on this order => L#1: quantity is 4, L#2: quantity is 1
        // from order_lot_numbers, L#2 should now have 1 available_qty, and L#1 will have 148
        // --- do this tomorrow ---
        //


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

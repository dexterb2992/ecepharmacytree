<?php

namespace ECEPharmacyTree\Http\Controllers;

use Request;

use ECEPharmacyTree\Http\Requests;
use ECEPharmacyTree\Http\Controllers\Controller;
use ECEPharmacyTree\Basket;
use DB;
use Input;

class BasketController extends Controller
{

   function check_basket(){
    $input = Input::all();

    $response = array();

    $results = DB::select("call check_basket(".$input['patient_id'].", ".$input['branch_id'].")");

    foreach($results as $result){
        if($result->quantity > $result->available_quantity) {
            $basket = Basket::findOrFail($result->basket_id);
            $basket->quantity = $result->available_quantity;
            if($basket->save()){
                $result->quantity = $result->available_quantity;                    
            }
        }

    }
    $response['baskets'] = $results;
    $response['success'] = 1;
    $response['server_timestamp'] = date("Y-m-d H:i:s", time());

    return json::encode($response);
}

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}

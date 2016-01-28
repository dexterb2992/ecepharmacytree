<?php

namespace ECEPharmacyTree\Http\Controllers;

use Request;

use ECEPharmacyTree\Http\Requests;
use ECEPharmacyTree\Http\Controllers\Controller;
use ECEPharmacyTree\Basket;
use DB;
use Input;
use ECEPharmacyTree\Repositories\BasketRepository;
use ECEPharmacyTree\Repositories\PointsRepository;

class BasketController extends Controller
{
    function __construct(BasketRepository $basket, PointsRepository $points) {
        $this->basket = $basket;
        $this->points = $points;
    }

    function compute_basket_points(){
        return $this->points->compute_basket_points(Input::all());
    }

    function check_basket(){
        $input = Input::all();

        $response = json_decode($this->basket->check_and_adjust_basket($input['patient_id'], $input['branch_id']));

        return json_encode($response);
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

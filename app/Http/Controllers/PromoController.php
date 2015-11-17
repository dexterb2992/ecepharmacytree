<?php

namespace ECEPharmacyTree\Http\Controllers;

use Carbon\Carbon;
use Input;
use Redirect;
use Request;

use ECEPharmacyTree\Http\Controllers\Controller;
use ECEPharmacyTree\Promo;
use ECEPharmacyTree\Product;


class PromoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {

        $promos = Promo::all();
        $products = Product::all();

        return view('admin.promo')->withPromos($promos)->withTitle('Promotions and Discounts')
            ->withProducts($products);
        // return view('admin.promo');
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
    public function store()
    {
        $input = Input::all();
        $promo = new Promo;
        $promo->name = $input["name"];
        $promo->start_date = $input["start_date"];
        $promo->end_date = $input["end_date"];

        if( $promo->save() ){
            session()->flash("flash_message", ["msg" => "New promo has been added successfully.", "type" => "success"]);
            return Redirect::to( route('Promo::index') );
        }

        session()->flash("flash_message", ["msg" => "Sorry, we can't process your request right now. Please try again later.", "type" => "warning"]);
        return Redirect::to( route('Promo::index') );

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {

        $promo = Promo::find($id);
        if( isset( $promo->id ) )
            return $promo->toJson();
        
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
    public function update()
    {
        $input = Input::all();
        $promo = Promo::findOrFail($input['id']);
        $promo->name = $input["name"];
        $promo->start_date = $input["start_date"];
        $promo->end_date = $input["end_date"];

        if( $promo->save() ){
            session()->flash("flash_message", ["msg" => "Promo information has been updateed.", "type" => "info"]);
            return Redirect::to( route('Promo::index') );
        }
        session()->flash("flash_message", ["msg" => "Sorry, we can't process your request right now. Please try again later.", "type" => "warning"]);

        return Redirect::to( route('Promo::index') );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy()
    {
        $product = Promo::findOrFail(Input::get("id"));
        if( $product->delete() ){
            session()->flash("flash_message", ["msg" => "Promo has been successfully removed.", "type" => "danger"]);
            return json_encode( array("status" => "success") );
        }
        session()->flash("flash_message", ["msg" => "Sorry, we can't process your request right now. Please try again later.", "type" => "warning"]);
        return json_encode( array("status" => "failed", "msg" => "Sorry, we can't process your request right now. Please try again later.") );
    }
}

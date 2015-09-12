<?php

namespace ECEPharmacyTree\Http\Controllers;

use Illuminate\Http\Request;

use ECEPharmacyTree\Http\Requests;
use ECEPharmacyTree\Http\Controllers\Controller;
use ECEPharmacyTree\Promo;
use Carbon\Carbon;
use Input;
use Redirect;

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

        return view('admin.promo')->withPromos($promos)->withTitle('Promo and Discounts');
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
            return Redirect::to( route('Promo::index') );
        }

        return Redirect::to( route('Promo::index') )
            ->withFlashMessage("Sorry, we can't process your request right now. Please try again later.");

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
            return Redirect::to( route('Promo::index') );
        }

        return Redirect::to( route('Promo::index') )
            ->withFlashMessage("Sorry, we can't process your request right now. Please try again later.");
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
            return json_encode( array("status" => "success") );
        }
        return json_encode( array("status" => "failed", "msg" => "Sorry, we can't process your request right now. Please try again later.") );
    }
}

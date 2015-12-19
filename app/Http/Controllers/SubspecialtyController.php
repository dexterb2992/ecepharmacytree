<?php

namespace ECEPharmacyTree\Http\Controllers;

use Request;
use Redirect;
use Input;

use ECEPharmacyTree\Http\Requests;
use ECEPharmacyTree\Http\Controllers\Controller;

use ECEPharmacyTree\Specialty;
use ECEPharmacyTree\Subspecialty;

class SubspecialtyController extends Controller
{
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
        $subspecialty = new Subspecialty;
        $subspecialty->name = Input::get('name');
        $subspecialty->specialty_id = Input::get('specialty_id');
        if( $subspecialty->save() )
            return Redirect::to( route('doctors') );
        return false;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $subspecialty = Subspecialty::find($id);
        return $subspecialty->toJson();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit()
    {
       
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
        $subspecialty = Subspecialty::find( Input::get('id') );
        $subspecialty->name = Input::get('name');
        $subspecialty->specialty_id = Input::get('specialty_id');
        if( $subspecialty->save() )
        return Redirect::to( route('doctors') );
        return false;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy()
    {
        if( Subspecialty::destroy( Input::get('id') ) )
            return json_encode( array("status" => "success") );

        return json_encode( array("status" => "failed", "msg" => "Sorry, we can't process your request right now. Please try again later.") );
    }
}

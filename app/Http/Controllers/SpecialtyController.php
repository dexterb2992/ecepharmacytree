<?php

namespace ECEPharmacyTree\Http\Controllers;

use Illuminate\Http\Request;

use ECEPharmacyTree\Http\Requests;
use ECEPharmacyTree\Http\Controllers\Controller;

use Redirect;
use Input;
use ECEPharmacyTree\Specialty;
use ECEPharmacyTree\Subspecialty;

class SpecialtyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $specialties = Specialty::all();
        $subspecialties = Subspecialty::all();

        $specialty_names = array();
        foreach ($specialties as $specialty) {
            $specialty_names[$specialty->id] = $specialty->name;
        }

        return view('admin.doctor-specialties')->withSpecialties($specialties)->withSubspecialties($subspecialties)
            ->withSpecialty_names($specialty_names);
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
        $specialty = new Specialty;
        $specialty->name = Input::get('name');
        if( $specialty->save() ){
           return Redirect::to( route('DoctorSpecialty::index') );
        }
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
        $specialty = Specialty::find($id);
        return $specialty->toJson();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit()
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
        $specialty = Specialty::find( Input::get('id') );
        $specialty->name = Input::get('name');
        if( $specialty->save() ){
           return Redirect::to( route('DoctorSpecialty::index') );
        }
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
        if( Specialty::destroy( Input::get( 'id' ) ) ){
            return json_encode( array("status" => "success") );
        }
        return json_encode( array("status" => "failed", "msg" => "Sorry, we can't process your request right now. Please try again later.") );
    }
}

<?php

namespace ECEPharmacyTree\Http\Controllers;

use Illuminate\Http\Request;

use ECEPharmacyTree\Http\Requests;
use ECEPharmacyTree\Http\Controllers\Controller;

use ECEPharmacyTree\Clinic;

class ClinicController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $clinics = Clinic::all();
              return view('admin.clinics')->withClinics($clinics);
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
        $input = Input::all();
        $clinic = new Clinic();
        $clinic->name = ucfirst( $input['name'] );
        $clinic->contact_no = ucfirst( $input['contact_no'] );
        $clinic->unit_floor_room_no = ucfirst( $input['unit_floor_room_no'] );
        $clinic->building = $input['building'];
        $clinic->lot_no = $input['lot_no'];
        $clinic->block_no = $input['block_no'];
        $clinic->phase_no = $input['phase_no'];
        $clinic->address_house_no = $input['address_house_no'];
        $clinic->address_street = $input['address_street'];
        $clinic->address_barangay = $input['address_barangay'];
        $clinic->address_city_municipality = $input['address_city_municipality'];
        $clinic->address_province = $input['address_province'];
        $clinic->address_region = $input['address_region'];
        $clinic->address_zip = $input['address_zip'];

        if( $clinic->save() )
            return Redirect::to( route('clinics') );
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
        $clinic = Clinic::findOrFail($id);
        if( isset( $clinic->id ) )
            return $clinic->toJson();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $input = Input::all();
        $clinic = Clinic::findOrFail($input['id']);
        $clinic->name = ucfirst( $input['name'] );
        $clinic->contact_no = ucfirst( $input['contact_no'] );
        $clinic->unit_floor_room_no = ucfirst( $input['unit_floor_room_no'] );
        $clinic->building = $input['building'];
        $clinic->lot_no = $input['lot_no'];
        $clinic->block_no = $input['block_no'];
        $clinic->phase_no = $input['phase_no'];
        $clinic->address_house_no = $input['address_house_no'];
        $clinic->address_street = $input['address_street'];
        $clinic->address_barangay = $input['address_barangay'];
        $clinic->address_city_municipality = $input['address_city_municipality'];
        $clinic->address_province = $input['address_province'];
        $clinic->address_region = $input['address_region'];
        $clinic->address_zip = $input['address_zip'];

        if( $clinic->save() )
            return Redirect::to( route('clinics') );
        return false;
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

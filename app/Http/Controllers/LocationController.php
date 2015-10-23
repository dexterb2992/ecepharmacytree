<?php

namespace ECEPharmacyTree\Http\Controllers;
use Input;
use Request;
use DB;

use ECEPharmacyTree\Http\Requests;
use ECEPharmacyTree\Http\Controllers\Controller;
use ECEPharmacyTree\Region;
use ECEPharmacyTree\Province;
use ECEPharmacyTree\Municipality;
use ECEPharmacyTree\Barangay;


class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param string $get_location
     * @param string $parent_location
     * @param  int  $parent_location_id
     * @return \Illuminate\Http\Response
     */
    public function show($get_location = 'regions', $parent_location = '', $parent_location_id = 0)
    {   
        if( $get_location == "regions" )
            $response =  Region::all()->toArray();

        else if( $get_location == "provinces" && $parent_location == "regions")
            
            $response =  Region::findOrFail($parent_location_id)->provinces->toArray(); 

        else if( $get_location == "municipalities" && $parent_location == "provinces" )

            $response =  Province::findOrFail($parent_location_id)->municipalities->toArray();

        else if( $get_location == "barangays" && $parent_location == "municipalities" )
            
            $response = Municipality::findOrFail($parent_location_id)->barangays->toArray();

        return $response = decode_utf8($response);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

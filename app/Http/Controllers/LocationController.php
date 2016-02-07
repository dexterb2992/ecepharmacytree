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

    public function search($get_location, $location_name){
        if( $get_location == "regions" )
            $response =  Region::all()->toArray();

        else if( $get_location == "provinces")
            
            $response =  Province::where('name', $location_name)->get()->toArray(); 

        else if( $get_location == "municipalities")

            $response =  Municipality::where('name', $location_name)->get()->toArray(); 

        else if( $get_location == "barangays" )
            
            $response = Barangay::where('name', $location_name)->get()->toArray(); 

        return $response = decode_utf8($response);
    }


    public function populate_address_by_barangay($barangay_id){
        $barangay = Barangay::findOrFail($barangay_id);

        $barangays = Barangay::where('municipality_id', '=', $barangay->municipality->id)->get();
        $municipalities = Municipality::where('province_id', '=', $barangay->municipality->province->id)->get();
        $provinces = Province::where('region_id', '=', $barangay->municipality->province->region->id)->get();

        $selected = [
            "barangay_id" => $barangay->id,
            "municipality_id" => $barangay->municipality->id,
            "province_id" => $barangay->municipality->province->id,
            "region_id" => $barangay->municipality->province->region->id
        ];

        $response = array(
            "barangays" => $barangays,
            "municipalities" => $municipalities,
            "provinces" => $provinces,
            "selected" => $selected
        );

        return json_encode($response);
    }
}

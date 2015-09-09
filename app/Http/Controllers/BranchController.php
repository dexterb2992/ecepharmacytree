<?php

namespace ECEPharmacyTree\Http\Controllers;

use Request;
use ECEPharmacyTree\Branch;
use Input;
use Redirect;

use ECEPharmacyTree\Http\Requests;
use ECEPharmacyTree\Http\Controllers\Controller;

class BranchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(){
        //
        $branches = Branch::all();
        return view('admin.branches')->withBranches($branches);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(){
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request){
        $input = Input::all();
        $branch = new Branch;
        $branch->name = $input["name"];
        $branch->unit_floor_room_no = $input["unit_floor_room_no"];
        $branch->building = $input["building"];
        $branch->lot_no = $input["lot_no"];
        $branch->block_no = $input["block_no"];
        $branch->phase_no = $input["phase_no"];
        $branch->address_street = $input["address_street"];
        $branch->address_barangay = $input["address_barangay"];
        $branch->address_city_municipality = $input["address_city_municipality"];
        $branch->address_province = $input["address_province"];
        $branch->address_region = $input["address_region"];
        $branch->address_zip = $input["address_zip"];
        if( $branch->save() ) 
            return Redirect::to( route('Branches::index') );
        
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
        $branch = Branch::find($id);
        return $branch->toJson();
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
        $input = Input::all();
        $branch = Branch::find($input["id"]);
        $branch->name = $input["name"];
        $branch->unit_floor_room_no = $input["unit_floor_room_no"];
        $branch->building = $input["building"];
        $branch->lot_no = $input["lot_no"];
        $branch->block_no = $input["block_no"];
        $branch->phase_no = $input["phase_no"];
        $branch->address_street = $input["address_street"];
        $branch->address_barangay = $input["address_barangay"];
        $branch->address_city_municipality = $input["address_city_municipality"];
        $branch->address_province = $input["address_province"];
        $branch->address_region = $input["address_region"];
        $branch->address_zip = $input["address_zip"];
        if( $branch->save() ) 
            return Redirect::to( route('Branches::index') );
        
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

        if( Request::ajax() ){
            if( Branch::destroy( Input::get('id') ) ) 
                return json_encode( array("status" => "success") );
        }
        return json_encode( array("status" => "failed", "msg" => "Sorry, we can't process your request right now. Please try again later.") );
    }

    public function activate_deactivate(){

        if( Request::ajax() ){
            $branch = Branch::find( Input::get('id') );
            $branch->status = $branch->status == 0 ? 1 : 0;
            if( $branch->save() ) 
            return json_encode( array("status" => "success") );
        }
        
        return json_encode( array("status" => "failed", "msg" => "Sorry, we can't process your request right now. Please try again later.") );
    }
}

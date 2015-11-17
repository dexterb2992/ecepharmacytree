<?php

namespace ECEPharmacyTree\Http\Controllers;

use Request;
use Input;
use Redirect;
use Auth;

use ECEPharmacyTree\Http\Requests;
use ECEPharmacyTree\Http\Controllers\Controller;
use ECEPharmacyTree\Branch;
use ECEPharmacyTree\Region;
use ECEPharmacyTree\Municipality;
use ECEPharmacyTree\Province;

class BranchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(){
        //
        $regions = Region::all();
        if( Auth::user()->isAdmin() ){
            $branches = Branch::all();
        }else if( Auth::user()->isBranchManager() ){
            $branches = Branch::find( Auth::user()->branch->id );
        }else{
            abort(404);
        }


        return view('admin.branches')->withBranches($branches)
            ->withRegions($regions);
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

        $branch->additional_address =  $input["additional_address"];
        $branch->barangay_id = $input['barangay_id'];
        $branch->latitude = $input['google_lat'];
        $branch->longitude = $input['google_lng'];
        $branch->telephone_numbers = $input['telephone_numbers'];
        $branch->telefax = $input['telefax'];
        $branch->mobile_numbers = $input['mobile_numbers'];

        if( $branch->save() )
            session()->flash("flash_message", array("msg" => "New branch has been added successfully.", "type" => "success"));
            return Redirect::to( route('Branches::index') );
        
        session()->flash("flash_message", array("msg" => "An error occured while processing your request. Please try again later.", "type" => "danger"));
        return Redirect::to( route('Branches::index') );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $branch = Branch::findOrFail($id);
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
        $branch = Branch::findOrFail($input["id"]);
        $branch->name = $input["name"];
        $branch->additional_address =  $input["additional_address"];
        $branch->barangay_id = $input['barangay_id'];
        
        // $branch->address_zip = $input["address_zip"];
        if( $branch->save() ) 
            session()->flash("flash_message", array("msg" => "Branch information has been updated successfully.", "type" => "success"));
            return Redirect::to( route('Branches::index') );
        
        return Redirect::back()->withInput()->withErrors()
            ->withFlash_message([
                "msg" => "Sorry, we can't process your request right now. Please try again later.",
                "type" => "danger"
            ]);
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
        
        session()->flash("flash_message", array("msg" => "Sorry, we can't process your request right now. Please try again later.", "type" => "warning"));
            return json_encode( array("status" => "failed", "msg" => "Sorry, we can't process your request right now. Please try again later.") );
    }

    public function activate_deactivate(){

        if( Request::ajax() ){
            $branch = Branch::findOrFail( Input::get('id') );
            $branch->status = $branch->status == 0 ? 1 : 0;
            if( $branch->save() ) 
                if( $branch->status == 0 ){
                    $flash_message = ["msg" => "Branch has been deactivated.", "type" => "warning"];
                }else{
                    $flash_message = ["msg" => "Branch has been activated.", "type" => "info"];

                }
                session()->flash("flash_message", $flash_message);
                return json_encode( array("status" => "success") );
        }

        session()->flash("flash_message", array("msg" => "Sorry, we can't process your request right now. Please try again later.", "type" => "danger"));
        return json_encode( array("status" => "failed", "msg" => "Sorry, we can't process your request right now. Please try again later.") );
    }

    public function get_which_branch(){
        $branches = Branch::all();
        return view('auth.choosebranch')->withBranches($branches);
    }
}

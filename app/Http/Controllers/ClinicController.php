<?php

namespace ECEPharmacyTree\Http\Controllers;

use Illuminate\Http\Request;

use Input;
use Redirect;

use ECEPharmacyTree\Http\Requests;
use ECEPharmacyTree\Http\Controllers\Controller;

use ECEPharmacyTree\Clinic;
use ECEPharmacyTree\Region;

use ECEPharmacyTree\Repositories\ClinicRepository;

class ClinicController extends Controller
{
    private $clinic;
    function __construct(ClinicRepository $clinic) {
        $this->clinic = $clinic;
        $this->middleware('admin');
    }


    public function index()
    {
        $result = $this->clinic->index();

        return view('admin.clinics')->withClinics($result['clinics'])
            ->withRegions($result['regions']);
    }


    public function store(Request $request)
    {
        $input = Input::all();

        $response = $this->clinic->store($input);

        if( $response )
            session()->flash('flash_message', ["msg" => "New Clinic has been added successfully.", "type" => "success"]);
            return Redirect::to( route('clinics') );
        
        session()->flash('flash_message', ["msg" => "Sorry, we can't process your request right now. Please try again later.", "type" => "danger"]);

        return false;
    }


    public function show($id)
    {
        $clinic = Clinic::findOrFail($id);
        if( isset( $clinic->id ) )
            return $clinic->toJson();
    }

    public function update()
    {
        $input = Input::all();
        
        $response = $this->clinic->update($input);

        if( $response )
            session()->flash("flash_message", ["msg" => "Clinic information has been updated.", "type" => "info"]);
            return Redirect::to( route('clinics') );
        session()->flash('flash_message', ["msg" => "Sorry, we can't process your request right now. Please try again later.", "type" => "danger"]);
        return false;
    }

    public function clinic_doctor(){
        $input = Input::all();

        if( $input['action'] == "get_doctors" ){
            $response = $this->clinic->get_doctors($input['clinic_id']);

            if( $response != false )
                return $response;

            return json_encode([
                "status" => "failed", 
                "msg" => "Sorry, we can't process your request right now. Please try again later."
            ]);
        }else if( $input['action'] == "apply_changes" ){
            $response = $this->clinic->clinic_doctor($input);

            return $response;
        }

       
    }


    public function destroy()
    {
        $clinic = Clinic::findOrFail(Input::get("id"));
        if( $clinic->delete() ){
            session()->flash("flash_message", ["msg" => "Clinic has been deleted successfully.", "type" => "warning"]);
            return json_encode(["status" => "success"]);
        }
        
        session()->flash('flash_message', ["msg" => "Sorry, we can't process your request right now. Please try again later.", "type" => "danger"]);

        return json_encode([
            "status" => "failed", 
            "msg" => "Sorry, we can't process your request right now. Please try again later."
        ]);
    }
}

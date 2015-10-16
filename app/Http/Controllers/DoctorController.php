<?php

namespace ECEPharmacyTree\Http\Controllers;

use Request;

use ECEPharmacyTree\Http\Requests;
use ECEPharmacyTree\Http\Controllers\Controller;

use Redirect;
use Input;
use ECEPharmacyTree\Specialty;
use ECEPharmacyTree\SubSpecialty;
use ECEPharmacyTree\Doctor;


class DoctorController extends Controller
{
    function __construct() {
        $this->middleware('admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $doctors = Doctor::all();
        $specialties = Specialty::all();
        $subspecialties = SubSpecialty::all();

        $specialty_names = array();
        foreach ($specialties as $specialty) {
            $specialty_names[$specialty->id] = $specialty->name;
        }

        return view('admin.doctors')->withDoctors($doctors)->withSpecialties($specialties)->withSubspecialties($subspecialties)
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
        $input = Input::all();
        $doctor = new Doctor();
        $doctor->lname = ucfirst( $input['lname'] );
        $doctor->mname = ucfirst( $input['mname'] );
        $doctor->fname = ucfirst( $input['fname'] );
        $doctor->prc_no = $input['prc_no'];
        $doctor->sub_specialty_id = $input['sub_specialty_id'];
        $doctor->affiliation = $input['affiliation'];
        $doctor->email = $input['email'];

        if( $doctor->save() )
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
        // $specialty = Specialty::find($id);
        $doctor = Doctor::findOrFail($id);

        return $doctor->toJson();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit()
    {
        $input = Input::all();
        $doctor = Doctor::findOrFail( $input['id'] );
        $doctor->lname = ucfirst( $input['lname'] );
        $doctor->mname = ucfirst( $input['mname'] );
        $doctor->fname = ucfirst( $input['fname'] );
        $doctor->prc_no = $input['prc_no'];
        $doctor->sub_specialty_id = $input['sub_specialty_id'];
        $doctor->affiliation = $input['affiliation'];
        $doctor->email = $input['email'];

        if( $doctor->save() )
            return Redirect::to( route('doctors') );
        return false;
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
         return Redirect::to( route('doctor_specialties') );
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

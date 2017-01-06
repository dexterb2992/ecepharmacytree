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
use ECEPharmacyTree\Employee;


class DoctorController extends Controller
{
    function __construct() {
        $this->middleware('admin');
    }

    public function index()
    {
        $doctors = Doctor::paginate(100);
        $specialties = Specialty::all();
        $subspecialties = SubSpecialty::all();

        $specialty_names = array();
        foreach ($specialties as $specialty) {
            $specialty_names[$specialty->id] = $specialty->name;
        }

        return view('admin.doctors')
            ->withDoctors($doctors)
            ->withSpecialties($specialties)
            ->withSubspecialties($subspecialties)
            ->withSpecialty_names($specialty_names)
            ->withTitle("Doctors");
    }


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
        $doctor->referral_id = generate_referral_id();
        if( $doctor->save() )
            $employee=new Employee();
            $employee->employeeno='00000';
            $employee->doctorlist_id=$doctor->id;
            $employee->lastname=$doctor->lname;
            $employee->firstname=$doctor->fname;
            $employee->middlename=$doctor->mname;
            $employee->licenceno=$doctor->prc_no;
            $employee->sub_specialtyid=$doctor->sub_specialty_id;
            $employee->email=$doctor->email;
            $employee->officecode='037';
            $employee->employeetypeid='111';
            $employee->save();
            return Redirect::to( route('doctors') );
        return false;
    }


    public function show($id)
    {
        // $specialty = Specialty::find($id);
        $doctor = Doctor::findOrFail($id);

        return $doctor->toJson();
    }

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


    public function update()
    {
        $specialty = Specialty::find( Input::get('id') );
        $specialty->name = Input::get('name');
        if( $specialty->save() ){
            return Redirect::to( route('doctor_specialties') );
        }
        return false;
    }

    public function get_all_doctors(){
        $doctors = Doctor::all();
        return $doctors;
    }


    public function destroy()
    {
        if( Specialty::destroy( Input::get( 'id' ) ) ){
            return json_encode( array("status" => "success") );
        }
        return json_encode( array("status" => "failed", "msg" => "Sorry, we can't process your request right now. Please try again later.") );
    }

    public function delete()
    {
        if( Doctor::destroy( Input::get( 'id' ) ) ){
            return json_encode( array("status" => "success") );
        }
        return json_encode( array("status" => "failed", "msg" => "Sorry, we can't process your request right now. Please try again later.") );
    }
}

<?php  
namespace ECEPharmacyTree\Repositories;

use Illuminate\Http\Request;

use Input;
use Redirect;

use ECEPharmacyTree\Http\Requests;
use ECEPharmacyTree\Http\Controllers\Controller;

use ECEPharmacyTree\Clinic;
use ECEPharmacyTree\Region;

class ClinicRepository{

	public function index(){
		$clinics = Clinic::with('doctors')->paginate(100);
        $regions = Region::all();

        return [
            'clinics' => $clinics,
    		'regions' => $regions
        ];
	}

	public function store($input){
		$clinic = new Clinic();
        $clinic->name = ucfirst( $input['name'] );
        $clinic->contact_no = ucfirst( $input['contact_no'] );
        $clinic->additional_address = ucfirst( $input['additional_address'] );
        $clinic->barangay_id = $input['barangay_id'];
        $clinic->cliniccode=$input['cliniccode'];
        if( $clinic->save() )
        	return true;
        return false;
	}

	public function update($input){
		$clinic = Clinic::findOrFail($input['id']);
        $clinic->name = ucfirst( $input['name'] );
        $clinic->contact_no = ucfirst( $input['contact_no'] );
        $clinic->additional_address = ucfirst( $input['additional_address'] );
        $clinic->barangay_id = $input['barangay_id'];

        if( $clinic->save() )
        	return true;
        return false;
	}

    public function clinic_doctor($input){
        $clinic = Clinic::find($input['clinic_id']);
        
        if( !isset($clinic->id) )
           return [
                'status' => 'failed',
                'msg' => "Could not find a Clinic with ID of {$input['clinic_id']}"
            ];

        if( isset( $input['doctor_ids'] ) && count( explode(',', $input['doctor_ids']) > 0 ) ){
            $clinic->doctors()->detach();

            $doctors = explode(',', $input['doctor_ids']);

            foreach ($doctors as $key => $value) {
                $clinic->doctors()->attach($value);
            }

            $_doctors = $clinic->doctors;

            return [
                'status' => 'success',
                'doctors' => $_doctors,
                'count' => count($_doctors)
            ];
        }

        return [
            'status' => 'failed',
            'msg' => "Something went wrong. Please try again later."
        ];
    }

    public function get_doctors($clinic_id){
        $clinic = Clinic::find($clinic_id);
        if( isset($clinic->id) )
            return $clinic->doctors;
        return false;
    }
}
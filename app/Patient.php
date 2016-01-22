<?php

namespace ECEPharmacyTree;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; 
use ECEPharmacyTree\Doctor;

class Patient extends Model
{
    use SoftDeletes;

    protected $table = "patients";
    

    public function basket(){
    	return $this->hasMany('ECEPharmacyTree\Basket');
    }

    public function patient_prescriptions(){
    	return $this->hasMany('ECEPharmacyTree\PatientPrescription');
    }

    public function records(){
    	return $this->hasMany('ECEPharmacyTree\PatientRecord');
    }

    public function orders(){
    	return $this->hasMany('ECEPharmacyTree\Order');
    }

    public function payments(){
        return $this->hasMany('ECEPharmacyTree\Order');
    }

    public function barangay(){
        return $this->belongsTo('ECEPharmacyTree\Barangay', 'address_barangay_id');
    }

    public function full_address(){
        if( !is_null($this->address_barangay_id) ){
            return $this->optional_address.", ".$this->barangay->name.", "
                .$this->barangay->municipality->name.", ".$this->barangay->municipality->province->name.", "
                .$this->barangay->municipality->province->region->name;
        }else{
            return $this->optional_address;
        }
    }
}

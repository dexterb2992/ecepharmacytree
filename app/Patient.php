<?php

namespace ECEPharmacyTree;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; 

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
    	return $this->hashMany('ECEPharmacyTree\Order');
    }

    
}

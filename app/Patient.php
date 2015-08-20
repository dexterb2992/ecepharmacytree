<?php

namespace ECEPharmacyTree;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $table = "patients";

    public function basket(){
    	return $this->hasMany('ECEPharmacyTree\Basket');
    }

    public function prescriptions(){
    	return $this->hasMany('ECEPharmacyTree\PatientPrescription');
    }

    public function records(){
    	return $this->hasMany('ECEPharmacyTree\PatientRecord');
    }

    public function orders(){
    	return $this->hashMany('ECEPharmacyTree\Order');
    }

    
}

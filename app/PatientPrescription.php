<?php

namespace ECEPharmacyTree;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes; 


class PatientPrescription extends Model
{
	// use SoftDeletes;
	
    protected $table = "patient_prescriptions";

    public function patient(){
    	return $this->belongsTo('ECEPharmacyTree\Patient');
    }

    public function basket(){
    	return $this->hasMany('ECEPharmacyTree\Basket');
    }

    public function order_detail(){
    	return $this->hasMany('ECEPharmacyTree\OrderDetail');
    }

}

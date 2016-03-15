<?php

namespace ECEPharmacyTree;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; 

class Doctor extends Model
{
	use SoftDeletes;
    protected $table = "doctors";
    protected $softDelete = true;

    public function subspecialty(){
    	return $this->belongsTo('ECEPharmacyTree\SubSpecialty', 'sub_specialty_id');
    }

    public function clinics(){
    	return $this->belongsToMany('ECEPharmacyTree\Clinic')->withPivot('doctor_id', 'clinic_id');;
    }

}

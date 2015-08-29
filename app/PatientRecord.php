<?php

namespace ECEPharmacyTree;

use Illuminate\Database\Eloquent\Model;

class PatientRecord extends Model
{
    protected $table = "patient_records";
    protected $softDelete = true;

    public function patient(){
    	return $this->belongsTo('ECEPharmacyTree\Patient');
    }

    public function treatments(){
    	return $this->hasMany('ECEPharmacyTree\Treatment');
    }
}

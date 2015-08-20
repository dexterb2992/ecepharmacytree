<?php

namespace ECEPharmacyTree;

use Illuminate\Database\Eloquent\Model;

class PatientPrescription extends Model
{
    protected $table = "patient_prescriptions";

    public function patient(){
    	return $this->belongsTo('ECEPharmacyTree\Patient');
    }
}

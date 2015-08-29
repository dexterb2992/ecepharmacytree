<?php

namespace ECEPharmacyTree;

use Illuminate\Database\Eloquent\Model;

class PatientPrescription extends Model
{
    protected $table = "patient_prescriptions";
    protected $softDelete = true;

    public function patient(){
    	return $this->belongsTo('ECEPharmacyTree\Patient');
    }
}

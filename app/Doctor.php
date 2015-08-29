<?php

namespace ECEPharmacyTree;

use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    protected $table = "doctors";
    protected $softDelete = true;

    public function subspecialty(){
    	return $this->hasOne('ECEPharmacyTree\SubSpecialty', 'sub_specialty_id');
    }
}

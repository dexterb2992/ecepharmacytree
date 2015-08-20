<?php

namespace ECEPharmacyTree;

use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    protected $table = "doctors";

    public function subspecialty(){
    	return $this->hasOne('ECEPharmacyTree\SubSpecialty', 'sub_specialty_id');
    }
}

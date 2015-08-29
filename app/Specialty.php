<?php

namespace ECEPharmacyTree;

use Illuminate\Database\Eloquent\Model;

class Specialty extends Model
{
    protected $table = "specialties";
    protected $softDelete = true;

    public function subspecialties(){
    	return $this->hasMany('ECEPharmacyTree\SubSpecialty');
    }
}

<?php

namespace ECEPharmacyTree;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; 

class Specialty extends Model
{
    protected $table = "specialties";
    protected $softDelete = true;

    public function subspecialties(){
    	return $this->hasMany('ECEPharmacyTree\SubSpecialty');
    }
}

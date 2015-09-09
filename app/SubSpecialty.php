<?php

namespace ECEPharmacyTree;

use Illuminate\Database\Eloquent\Model;

class SubSpecialty extends Model
{
    protected $table = "sub_specialties";
    protected $softDelete = true;

    public function doctors(){
    	return $this->hasMany('ECEPharmacyTree\Doctor');
    }

    public function specialty(){
    	return $this->belongsTo('ECEPharmacyTree\Specialty');
    }
}

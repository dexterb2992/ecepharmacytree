<?php

namespace ECEPharmacyTree;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; 

class Branch extends Model
{
    protected $table = "branches";
    protected $softDelete = true;

    public function users(){
    	return $this->hasMany('ECEPharmacyTree\User');
    }

    function orders(){
    	return $this->hasMany('ECEPharmacyTree\Order');
    }
}

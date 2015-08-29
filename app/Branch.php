<?php

namespace ECEPharmacyTree;

use Illuminate\Database\Eloquent\Model;
use SoftDeletes;

class Branch extends Model
{
    protected $table = "branches";
    protected $softDelete = true;

    public function users(){
    	return $this->hasMany('ECEPharmacyTree\User');
    }
}

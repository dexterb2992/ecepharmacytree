<?php

namespace ECEPharmacyTree;

use Illuminate\Database\Eloquent\Model;

class Beneficiaries extends Model
{
    protected $table = "beneficiaries";
    protected $softDelete = true;

    public function patient(){
    	return $this->belongsTo('ECEPharmacyTree\Patient');
    }
}

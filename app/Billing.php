<?php

namespace ECEPharmacyTree;

use Illuminate\Database\Eloquent\Model;

class Billing extends Model
{
    protected $table = "billings";
    protected $softDelete = true;

    public function order(){
    	return $this->belongsTo('ECEPharmacyTree\Order');
    }
}

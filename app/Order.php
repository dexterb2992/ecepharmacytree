<?php

namespace ECEPharmacyTree;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = "orders";
    protected $softDelete = true;

    public function details(){
    	return $this->hasMany('ECEPharmacyTree\OrderDetail');
    }

    public function patient(){
    	return $this->belongsTo('ECEPharmacyTree\Patient');
    }

    public function billing(){
    	return $this->belongsTo('ECEPharmacyTree\Billing');
    }
}

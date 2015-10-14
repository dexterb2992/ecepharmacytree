<?php

namespace ECEPharmacyTree;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = "orders";
    protected $softDelete = true;

    public function order_details(){
    	return $this->hasMany('ECEPharmacyTree\OrderDetail');
    }

    public function patient(){
    	return $this->belongsTo('ECEPharmacyTree\Patient', 'patient_id');
    }

    public function billing(){
    	return $this->hasOne('ECEPharmacyTree\Billing', 'id');
    }

    function branch() {
        return $this->belongsTo('ECEPharmacyTree\Branch', 'branch_id');
    }
}

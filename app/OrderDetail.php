<?php

namespace ECEPharmacyTree;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    protected $table = "order_details";

    public function order(){
    	return $this->belongsTo('ECEPharmacyTree\Order');
    }

    public function product(){
    	return $this->hasOne('ECEPharmacyTree\Product');
    }
}

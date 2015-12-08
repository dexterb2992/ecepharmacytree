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
    	return $this->belongsTo('ECEPharmacyTree\Patient');
    }

    public function billing(){
    	return $this->hasOne('ECEPharmacyTree\Billing', 'id');
    }

    public function branch() {
        return $this->belongsTo('ECEPharmacyTree\Branch', 'branch_id');
    }

    public function stock_returns(){
        return $this->hasMany('ECEPharmacyTree\StockReturn');
    }

}

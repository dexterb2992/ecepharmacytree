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
    	return $this->hasOne('ECEPharmacyTree\Billing');
    }

    public function branch() {
        return $this->belongsTo('ECEPharmacyTree\Branch');
    }

    public function stock_returns(){
        return $this->hasMany('ECEPharmacyTree\StockReturn');
    }

    public function lot_numbers(){
        return $this->hasMany('ECEPharmacyTree\OrderLotNumber');
    }

}

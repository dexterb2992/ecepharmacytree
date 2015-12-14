<?php

namespace ECEPharmacyTree;

use Illuminate\Database\Eloquent\Model;

class OrderLotNumber extends Model
{
    protected $guarded = ['id'];

    protected $table = "order_lot_numbers";

    public function order(){
    	return $this->belongsTo('ECEPharmacyTree\Order');
    }

    public function inventory(){
    	return $this->belongsTo('ECEPharmacyTree\Inventory');
    }
}

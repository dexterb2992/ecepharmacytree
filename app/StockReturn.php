<?php

namespace ECEPharmacyTree;

use Illuminate\Database\Eloquent\Model;

class StockReturn extends Model
{
    protected $guarded = ['id'];
    protected $table = "stock_returns";

    public function order(){
    	return $this->belongsTo('ECEPharmacyTree\Order');
    }

    public function inventory(){
    	return $this->belongsTo('ECEPharmacyTree\Inventory');
    }

    public function product_stock_returns(){
    	return $this->hasMany('ECEPharmacyTree\ProductStockReturn');
    }
}

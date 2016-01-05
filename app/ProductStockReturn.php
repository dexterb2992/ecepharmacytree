<?php

namespace ECEPharmacyTree;

use Illuminate\Database\Eloquent\Model;

class ProductStockReturn extends Model
{
    protected $table = "product_stock_returns";
    protected $guarded = ['id'];

    public function stock_return(){
    	return $this->belongsTo('ECEPharmacyTree\StockReturn');
    }

    public function product(){
    	return $this->has('ECEPharmacyTree\Product');
    }
}

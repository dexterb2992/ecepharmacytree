<?php

namespace ECEPharmacyTree;

use Illuminate\Database\Eloquent\Model;

class DiscountsFreeProduct extends Model
{
    protected $table = "discounts_free_products";
    protected $softDelete = true;

    public function product(){
    	return $this->belongsTo('ECEPharmacyTree\Product');
    }

    public function promo(){
    	return $this->belongsTo('ECEPharmacyTree\Promo');
    }

    public function free_products(){
    	return $this->hasMany('ECEPharmacyTree\FreeProduct', 'dfp_id');
    }
}

<?php

namespace ECEPharmacyTree;

use Illuminate\Database\Eloquent\Model;

class DiscountsFreeProduct extends Model
{
    protected $table = "discounts_free_products";

    public function promo(){
    	return $this->belongsTo('ECEPharmacyTree\Promo');
    }

    public function freeProduct(){
    	return $this->hasMany('ECEPharmacyTree\FreeProduct', 'dfp_id');
    }
}

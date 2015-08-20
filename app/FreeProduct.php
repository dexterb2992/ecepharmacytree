<?php

namespace ECEPharmacyTree;

use Illuminate\Database\Eloquent\Model;

class FreeProduct extends Model
{
    protected $table = "free_products";

    public function dfp(){
    	return $this->belongsTo('ECEPharmacyTree\DiscountsFreeProducts', 'dfp_id');
    }
}

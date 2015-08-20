<?php

namespace ECEPharmacyTree;

use Illuminate\Database\Eloquent\Model;

class Promo extends Model
{
    protected $table = "promos";

    public function discounts(){
    	return $this->hasMany('ECEPharmacyTree\DiscountsFreeProducts');
    }
}

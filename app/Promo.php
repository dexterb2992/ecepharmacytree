<?php

namespace ECEPharmacyTree;

use Illuminate\Database\Eloquent\Model;

class Promo extends Model
{
    protected $table = "promos";
    protected $softDelete = true;

    public function discounts(){
    	return $this->hasMany('ECEPharmacyTree\DiscountsFreeProducts');
    }
}

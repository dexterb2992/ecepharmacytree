<?php

namespace ECEPharmacyTree;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; 

class Promo extends Model
{
	use SoftDeletes;
    protected $table = "promos";
    protected $softDelete = true;
    protected $dates = ['dob'];

    public function discounts(){
    	return $this->hasMany('ECEPharmacyTree\DiscountsFreeProducts');
    }
}

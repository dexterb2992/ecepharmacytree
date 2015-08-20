<?php

namespace ECEPharmacyTree;

use Illuminate\Database\Eloquent\Model;

class Basket extends Model
{
    protected $table = "baskets";

    /**
	 * Get products associated with the basket
     */
    public function products(){
    	return $this->hasOne('ECEPharmacyTree\Product', 'product_id');
    }

    public function patient(){
    	return $this->belongsTo('ECEPharmacyTree\Patient');
    }
}

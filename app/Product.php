<?php

namespace ECEPharmacyTree;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = "products";

    public function subcategory(){
    	return $this->belongsTo('ECEPharmacyTree\ProductSubcategory', 'subcategory_id');
    }

    public function inventories(){
    	return $this->hasMany('ECEPharmacyTree\Inventory');
    }
}

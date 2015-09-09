<?php

namespace ECEPharmacyTree;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; 

class Product extends Model
{
	use SoftDeletes;
    protected $table = "products";
    protected $softDelete = true;

    public function subcategory(){
    	return $this->belongsTo('ECEPharmacyTree\ProductSubcategory', 'subcategory_id');
    }

    public function inventories(){
    	return $this->hasMany('ECEPharmacyTree\Inventory');
    }
}

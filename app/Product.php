<?php

namespace ECEPharmacyTree;

use Illuminate\Database\Eloquent\Model;
use SoftDeletes;

class Product extends Model
{
    protected $table = "products";
    protected $softDelete = true;

    public function subcategory(){
    	return $this->belongsTo('ECEPharmacyTree\ProductSubcategory', 'subcategory_id');
    }

    public function inventories(){
    	return $this->hasMany('ECEPharmacyTree\Inventory');
    }
}

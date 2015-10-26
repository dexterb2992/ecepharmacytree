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
    
    public function basket(){
    	return $this->hasMany('ECEPharmacyTree\Basket');
    }

    public function order_details(){
        return $this->hasMany('ECEPharmacyTree\OrderDetail');
    }

    public function gallery(){
        return $this->hasMany('ECEPharmacyTree\ProductsGallery');
    }
}

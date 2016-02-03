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

    public function galleries(){
        return $this->hasMany('ECEPharmacyTree\ProductsGallery');
    }

    public function group(){
        return $this->belongsTo('ECEPharmacyTree\ProductGroup');
    }

    public function discount_free_product(){
        return $this->hasMany('ECEPharmacyTree\DiscountsFreeProduct');
    }

    public function free_product(){
        return $this->hasMany('ECEPharmacyTree\FreeProduct');
    }

    public function stock_return(){
        return $this->hasMany('ECEPharmacyTree\ProductStockReturn');    
    }

}

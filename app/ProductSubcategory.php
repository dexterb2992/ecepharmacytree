<?php

namespace ECEPharmacyTree;

use Illuminate\Database\Eloquent\Model;

class ProductSubcategory extends Model
{
    protected $table = "product_subcategories";
    protected $softDelete = true;

    public function category(){
    	return $this->belongsTo('ECEPharmacyTree\ProductCategory', 'category_id');
    }

    public function products(){
    	return $this->hasMany('ECEPharmacyTree\Product');
    }
}

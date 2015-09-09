<?php

namespace ECEPharmacyTree;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; 

class ProductCategory extends Model
{
    protected $table = "product_categories";
    protected $softDelete = true;

    public function subcategories(){
    	return $this->hasMany('ECEPharmacyTree\ProductSubcategory', 'category_id');
    }
}

<?php

namespace ECEPharmacyTree;

use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    protected $table = "product_categories";
    protected $softDelete = true;

    public function subcategories(){
    	return $this->hasMany('ECEPharmacyTree\ProductSubcategories', 'category_id');
    }
}

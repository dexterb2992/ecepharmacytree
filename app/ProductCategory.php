<?php

namespace ECEPharmacyTree;

use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    protected $table = "product_categories";

    function subcategories(){
    	return $this->hasMany('ECEPharmacyTree\ProductSubcategories');
    }
}

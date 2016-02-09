<?php

namespace ECEPharmacyTree;

use Illuminate\Database\Eloquent\Model;

class BasketPromo extends Model
{
    protected $table = "basket_promos";

    // public function subcategories(){
    // 	return $this->hasMany('ECEPharmacyTree\ProductSubcategory', 'category_id');
    // }

    // function basket(){
    // 	return $this->belongsTo('ECEPharmacyTree\Basket', 'basket_id');
    // }
}

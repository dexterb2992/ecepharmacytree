<?php

namespace ECEPharmacyTree;

use Illuminate\Database\Eloquent\Model;

class ProductsGallery extends Model
{
    protected $guarded = ["id"];
    protected $table = "products_gallery";

    public function product(){
    	return $this->belongsTo('ECEPharmacyTree\Product');
    }
}

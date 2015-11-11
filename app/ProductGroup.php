<?php

namespace ECEPharmacyTree;

use Illuminate\Database\Eloquent\Model;

class ProductGroup extends Model
{
    protected $guarded = ['id'];
    protected $table = 'product_groups';

    public function products(){
    	return $this->hasMany('ECEPharmacyTree\Product');
    }
}

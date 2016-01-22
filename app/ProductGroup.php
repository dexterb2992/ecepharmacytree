<?php

namespace ECEPharmacyTree;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; 

class ProductGroup extends Model
{
	use SoftDeletes;
    protected $guarded = ['id'];
    protected $table = 'product_groups';

    public function products(){
    	return $this->hasMany('ECEPharmacyTree\Product');
    }
}

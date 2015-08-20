<?php

namespace ECEPharmacyTree;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $table = "inventories";

    public function product(){
    	return $this->belongsTo('ECEPharmacyTree\Product');
    }
}

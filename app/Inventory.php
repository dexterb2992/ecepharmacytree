<?php

namespace ECEPharmacyTree;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inventory extends Model
{

    protected $table = "inventories";
    protected $softDelete = true;

    public function product(){
    	return $this->belongsTo('ECEPharmacyTree\Product');
    }
}

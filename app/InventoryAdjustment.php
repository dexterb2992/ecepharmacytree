<?php

namespace ECEPharmacyTree;

use Illuminate\Database\Eloquent\Model;

class InventoryAdjustment extends Model
{
    protected $guarded = ['id'];
    protected $table = "inventory_adjustments";

    public function inventory(){
    	return $this->belongsTo('ECEPharmacyTree\Inventory');
    }
}

<?php
namespace ECEPharmacyTree;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inventory extends Model
{
	use SoftDeletes;
	
    protected $table = "inventories";
    protected $softDelete = true;

    public function product(){
    	return $this->belongsTo('ECEPharmacyTree\Product');
    }

    public function adjustments(){
    	return $this->hasMany('ECEPharmacyTree\InventoryAdjustment');
    }

    public function stock_returns(){
        return $this->hasMany('ECEPharmacyTree\StockReturn');
    }
}

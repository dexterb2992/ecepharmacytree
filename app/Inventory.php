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

    public function lot_number(){
        return $this->hasOne('ECEPharmacyTree\OrderLotNumber');
    }

    public function product_stock_returns(){
        return $this->belongsTo('ECEPharmacyTree\ProductStockReturn');
    }
}

<?php
namespace ECEPharmacyTree;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; 

class Branch extends Model
{
    protected $table = "branches";
    protected $softDelete = true;

    public function users(){
    	return $this->hasMany('ECEPharmacyTree\User', 'branch_id');
    }

    public function orders(){
    	return $this->hasMany('ECEPharmacyTree\Order');
    }

    public function barangay(){
    	return $this->belongsTo('ECEPharmacyTree\Barangay');
    }

    public function business_hours(){
    	return $this->hasMany('ECEPharmacyTree\BusinessHour');
    }
}

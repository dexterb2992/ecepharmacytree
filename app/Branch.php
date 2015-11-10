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

    public function full_address(){
        if( !is_null($this->barangay_id) ){
            return $this->additional_address.", ".$this->barangay->name.", "
                .$this->barangay->municipality->name.", ".$this->barangay->municipality->province->name.", "
                .$this->barangay->municipality->province->region->name;
        }else{
            return $this->additional_address;
        }
    }
}

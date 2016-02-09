<?php

namespace ECEPharmacyTree;

use Illuminate\Database\Eloquent\Model;

class Clinic extends Model
{
    protected $table = "clinics";
    protected $softDelete = true;

    public function barangay(){
        return $this->belongsTo('ECEPharmacyTree\Barangay');
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

    public function doctors(){
        return $this->belongsToMany('ECEPharmacyTree\Doctor');
    }
}

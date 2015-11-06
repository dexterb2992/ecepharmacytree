<?php
namespace ECEPharmacyTree;

use Illuminate\Database\Eloquent\Model;

class Barangay extends Model
{
    protected $guarded = ['id'];
    protected $table = "barangays";

    public function municipality(){
    	return $this->belongsTo('ECEPharmacyTree\Municipality');
    }

    public function branches(){
    	return $this->hasMany('ECEPharmacyTree\Branch');
    }
}

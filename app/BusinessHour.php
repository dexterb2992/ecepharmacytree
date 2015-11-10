<?php

namespace ECEPharmacyTree;

use Illuminate\Database\Eloquent\Model;

class BusinessHour extends Model
{
    protected $guarded = ['id'];
    protected $table = "business_hours";

    public function branch(){
    	return $this->belongsTo('ECEPharmacyTree\Branch');
    }
}

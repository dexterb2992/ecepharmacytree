<?php

namespace ECEPharmacyTree;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = "payments";
    protected $softDelete = true;

    public function billing(){
    	return $this->hasOne('ECEPharmacyTree\Billing');
    }


    /**
     * Get the user who assisted for the payment
     *
     */
    public function user(){
    	return $this->belongsTo('ECEPharmacyTree\User');
    }

}

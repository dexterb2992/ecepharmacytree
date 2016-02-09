<?php

namespace ECEPharmacyTree;

use Illuminate\Database\Eloquent\Model;
use SoftDeletes;

class Basket extends Model
{
    protected $table = "baskets";

    /**
	 * Get products associated with the basket
     */
    public function products(){
    	return $this->belongsTo('ECEPharmacyTree\Product', 'product_id');
    }

    public function patient(){
    	return $this->belongsTo('ECEPharmacyTree\Patient', 'patient_id');
    }

    public function patient_prescriptions(){
        return $this->belongsTo('ECEPharmacyTree\PatientPrescription', 'prescription_id');
    }

    function basket_promo(){
        return $this->hasOne('ECEPharmacyTree\BasketPromo', 'id');
    }

}

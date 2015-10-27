<?php

namespace ECEPharmacyTree;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    protected $table = "order_details";
    protected $softDelete = true;

    public function order(){
    	return $this->belongsTo('ECEPharmacyTree\Order', 'order_id');
    }

    public function product(){
    	return $this->belongsTo('ECEPharmacyTree\Product', 'product_id');
    }

    public function patient_prescriptions(){
        return $this->belongsTo('ECEPharmacyTree\PatientPrescription', 'prescription_id');
    }

}

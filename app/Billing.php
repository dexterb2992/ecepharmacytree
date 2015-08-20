<?php

namespace ECEPharmacyTree;

use Illuminate\Database\Eloquent\Model;

class Billing extends Model
{
    protected $table = "billings";

    public function order(){
    	return $this->hasOne('ECEPharmacyTree\Order');
    }
}

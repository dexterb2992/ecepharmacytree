<?php

namespace ECEPharmacyTree;

use Illuminate\Database\Eloquent\Model;

class ReferralCommissionActivityLog extends Model
{
    protected $guarded = ['id'];
    protected $table = 'referral_commission_activity_log';

    public function billing(){
    	return $this->belongsTo('ECEPharmacyTree\Billing');
    }
}

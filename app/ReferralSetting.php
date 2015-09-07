<?php

namespace ECEPharmacyTree;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; 


class ReferralSetting extends Model
{
	use SoftDeletes;
    protected $table = "referral_settings";
}

<?php

namespace ECEPharmacyTree;
use Illuminate\Database\Eloquent\SoftDeletes; 
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
	use SoftDeletes;
	protected $table = "settings";
}

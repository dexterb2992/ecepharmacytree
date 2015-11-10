<?php

namespace ECEPharmacyTree;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $guarded = ['id'];
    protected $table = "logs";

    public function user(){
    	return $this->belongsTo('ECEPharmacyTree\User');
    }
}

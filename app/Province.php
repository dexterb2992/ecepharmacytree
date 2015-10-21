<?php 
namespace ECEPharmacyTree;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    protected $guarded = ['id'];
    protected $table = "provinces";

    public function region(){
    	return $this->belongsTo('ECEPharmacyTree\Region');
    }

    public function municipalities(){
    	return $this->hasMany('ECEPharmacyTree\Municipality');
    }
}

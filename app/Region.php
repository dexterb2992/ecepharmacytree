<?php 
namespace ECEPharmacyTree;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    protected $guarded = ["id"];
    protected $table = "regions";

    public function provinces(){
    	return $this->hasMany('ECEPharmacyTree\Province');
    }
}

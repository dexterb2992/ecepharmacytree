<?php 
namespace ECEPharmacyTree;

use Illuminate\Database\Eloquent\Model;

class Municipality extends Model
{
    protected $guarded = ['id'];
    protected $table = 'municipalities';

    public function province(){
    	return $this->belongsTo('ECEPharmacyTree\Province');
    }
}

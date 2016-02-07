<?php

namespace ECEPharmacyTree;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\SoftDeletes; 

class User extends Model implements AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable, CanResetPassword;
    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    public function branch(){
        return $this->belongsTo('ECEPharmacyTree\Branch');
    }

    /**
     * Get the payments done by patients associated or assisted by specific user
     * Note: users are either Admin or Delivery Man
     */
    public function payments(){
        return $this->hasMany('ECEPharmacyTree\Payment');
    }

    /**
     * Returns user's activity history
     */
    public function logs(){
        return $this->hasMany('ECEPharmacyTree\Log');
    }

    /**
     * Check whether the user is Admin
     */
    public function isAdmin(){
        if( $this->access_level == "1" )
            return true;
        return false;
    }

    public function isBranchAdmin(){
        if( $this->access_level == "2" )
            return true;
        return false;
    }

    public function isPharmacist(){
        if( $this->access_level == "3" )
            return true;
        return false;
    }

    public function full_name($reversed = false){
        if( $reversed )
            return ucfirst($this->lname).", ".ucfirst($this->fname)." ".substr(ucfirst($this->mname), 0, 1).".";
        return ucfirst($this->fname)." ".substr(ucfirst($this->mname), 0, 1).". ".ucfirst($this->lname);
    }
}

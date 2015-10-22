<?php

namespace ECEPharmacyTree;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable, CanResetPassword;

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
    // protected $fillable = ['name', 'email', 'password'];
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
     * Check whether the user is Admin
     */
    public function isAdmin(){
        if( $this->access_level == "1" )
            return true;
        return false;
    }

    public function isBranchManager(){
        if( $this->access_level == "2" )
            return true;
        return false;
    }

    public function isPharmacist(){
        if( $this->access_level == "3" )
            return true;
        return false;
    }
}

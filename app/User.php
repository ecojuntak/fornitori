<?php

namespace App;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\EmailRegistration;

class User extends Authenticatable implements JWTSubject{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'email', 'password', 'role', 'status',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function getJWTIdentifier() {
        return $this->getKey();
    }

    public function getJWTCustomClaims() {
        return [];
    }

    public function profile() {
        return $this->hasOne('App\Profile');
    }

    public function emailVerification() {
        return $this->hasOne('App\VerifyUser');
    }

    public function products() {
        return $this->hasMany('App\Product');
    }

    public function carts() {
        return $this->hasMany('App\Cart');
    }

    public function sendEmailVerificationNotification() {
        $this->notify(new EmailRegistration($this));
    }
}

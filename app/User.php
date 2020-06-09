<?php

namespace App;

use App\Notifications\ResetPassword;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticable;
use Hyn\Tenancy\Traits\UsesTenantConnection;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Auth\MustVerifyEmail as TraitVerifyEmail;

class User extends Authenticable implements MustVerifyEmail
{
    use Notifiable, UsesTenantConnection, HasRoles, TraitVerifyEmail;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'updated_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function sendPasswordResetNotification($token){
        $this->notify(new ResetPassword($token));
        $this->markEmailAsVerified();
    }

    public function sendRegisterUserNotification(){
        $this->markEmailAsVerified();
    }

}

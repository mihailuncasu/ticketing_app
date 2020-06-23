<?php

namespace App;

use App\Notifications\ResetPassword as ResetPasswordNotification;
use App\Notifications\VerifyEmail as VerifyEmailNotification;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticable;
use Hyn\Tenancy\Traits\UsesTenantConnection;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Passport\HasApiTokens;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;

class User extends Authenticable implements MustVerifyEmail, HasMedia
{
    use Notifiable, UsesTenantConnection, HasRoles, HasApiTokens, HasMediaTrait;

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

    public function groups()
    {
        return $this
            ->belongsToMany(Group::class)
            ->withPivot(['added_by'])
            ->withTimestamps();
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    public function sendRegisterUserNotification()
    {
        $this->markEmailAsVerified();
    }

    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmailNotification); // my notification
    }

    public function registerMediaCollections()
    {
        $this
            ->addMediaCollection('avatar')
            ->acceptsMimeTypes(['image/jpeg', 'image/gif', 'image/png', 'image/bmp']);
    }

    public function registerMediaConversions(Media $media = null)
    {
        $this->addMediaConversion('thumb')
              ->width(100)
              ->height(100);

        $this->addMediaConversion('profile')
              ->width(200)
              ->height(200);
    }
}

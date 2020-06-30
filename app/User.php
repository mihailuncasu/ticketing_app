<?php

namespace App;

use App\Notifications\ResetPassword as ResetPasswordNotification;
use App\Notifications\VerifyEmail as VerifyEmailNotification;
use App\Traits\HasPermissions;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticable;
use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Support\Str;
use Laravel\Passport\HasApiTokens;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;

class User extends Authenticable implements MustVerifyEmail, HasMedia
{
    use Notifiable, UsesTenantConnection, HasApiTokens, HasMediaTrait, HasPermissions;

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

    /**
     * Eloquent relations.
     */
    public function groups()
    {
        return $this->belongsToMany(Group::class, 'groups_users')
            ->withPivot(['added_by'])
            ->withTimestamps();
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'users_roles');
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'users_permissions');
    }

    public function getAllPermissions() {
        $permissions = [];
        foreach($this->roles as $role) {
            foreach($role->permissions as $permission) {
                $permissions[] = $permission;
            }
        }
        foreach($this->permissions as $permission) {
            $permissions[] = $permission;
        }
        return array_unique($permissions);
    }

    public function getUserMenu() {
        $menuItems = [];
        $permissions = $this->getAllPermissions();
        foreach($permissions as $permission) {
            $permissionName = $permission->name;
            $groupSlug = $permission->group_slug;
            $menuTitle = Group::where('slug', $groupSlug)->first()->name;
            if (Str::startsWith($permissionName,'view') && Str::endsWith($permissionName, 'dashboard')) {
                $permissionTitle = Str::after($permissionName, 'view');
                $permissionTitle = Str::before($permissionTitle, 'dashboard');
                $permissionTitle = Str::title($permissionTitle);
                $permissionSlug = $permission->slug;
                $menuItems[$menuTitle][] = [
                    'title' => $permissionTitle,
                    'to' => "/$groupSlug/$permissionSlug",
                    'icon' => 'mdi-account'
                ];
            }
        }
        return $menuItems;
    }

    public function assignRolesUsingSlug($group_slug, ...$roles)
    {
        $roleIds = $this->getRoleIdsBySlug($group_slug, ...$roles);
        $groupRoleIds = $this->roles()->where('group_slug', $group_slug)->get()->pluck('id')->toArray();
        $removedRoleIds = array_diff($groupRoleIds, $roleIds);
        $this->roles()->detach($removedRoleIds);
        $this->roles()->syncWithoutDetaching($roleIds);
    }

    public function getRoleIdsBySlug($group_slug, $roles)
    {
        return Role::where('group_slug', $group_slug)
            ->whereIn('slug', $roles)
            ->get()
            ->pluck('id')
            ->toArray();
    }

    public function hasRole($group_slug, ...$roles)
    {
        // $user->hasRole('group-1', 'admin', 'developer');
        return $this->roles()
            ->where('group_slug', $group_slug)
            ->whereIn('slug', $roles)
            ->count();
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
        $this->addMediaCollection('avatar')
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

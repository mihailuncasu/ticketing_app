<?php

namespace App;

use Cviebrock\EloquentSluggable\Sluggable;
use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use UsesTenantConnection, Sluggable;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'pivot'
    ];

    protected $fillable = [
        'name', 'group_slug', 'updated_at', 'display_name'
    ];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'roles_permissions');
    }
}

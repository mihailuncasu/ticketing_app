<?php

namespace App;

use Cviebrock\EloquentSluggable\Sluggable;
use Hyn\Tenancy\Traits\UsesTenantConnection;
use Spatie\Permission\Models\Permission as SpatieModel;

class Permission extends SpatieModel
{
    use UsesTenantConnection, Sluggable;
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'guard_name', 'remember_token',
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
}

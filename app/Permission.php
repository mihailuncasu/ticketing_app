<?php

namespace App;

use Hyn\Tenancy\Traits\UsesTenantConnection;
use Spatie\Permission\Models\Permission as SpatieModel;

class Permission extends SpatieModel
{
    use UsesTenantConnection;
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'guard_name', 'remember_token',
    ];
}

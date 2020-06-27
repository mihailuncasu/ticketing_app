<?php

namespace App;

use Cviebrock\EloquentSluggable\Sluggable;
use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasPermissions;

class Role extends Model
{
    use UsesTenantConnection, Sluggable, HasPermissions;

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

    public function hasPermissionTo($group_slug, ...$permissions)
    {
        // $role->hasPermissionTo('edit-user', 'edit-issue');
        return $this->permissions()
            ->where('group_slug', $group_slug)
            ->whereIn('slug', $permissions)
            ->count();
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'roles_permissions');
    }

    public function scopeAdmin($query, $group_slug)
    {
        return $query->where([
            'group_slug' => $group_slug,
            'slug' => 'admin'
        ]);
    }
}

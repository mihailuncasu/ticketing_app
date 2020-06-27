<?php

namespace App\Traits;

use App\Permission;

trait HasPermissions
{
    public function hasPermissionTo($group_slug, ...$permissions)
    {
        // $user->hasPermissionTo('first-group','edit-user', 'edit-issue');
        return $this->permissions()
                ->where('group_slug', $group_slug)
                ->whereIn('slug', $permissions)
                ->count() ||
            $this->roles()
                ->where('group_slug', $group_slug)
                ->whereHas('permissions',
                    function ($q) use ($permissions) {
                        $q->whereIn('slug', $permissions);
                    })
                ->count();
    }

    /**
     * @param $group_slug
     * @param mixed ...$permissions is an array of permissions ids;
     */
    public function givePermissionsTo($permissions)
    {
        $this->permissions()->attach($permissions);
    }

    /**
     * @param $group_slug; is the group_slug.
     * @param mixed ...$permissions; is an array of slugs.
     */
    public function givePermissionsToUsingSlug($group_slug, ...$permissions)
    {
        $this->permissions()->sync($this->getPermissionIdsBySlug($group_slug, ...$permissions));
    }

    public function getPermissionIdsBySlug($group_slug, $permissions)
    {
        return Permission::where('group_slug', $group_slug)
            ->whereIn('slug', $permissions)
            ->get()
            ->pluck('id')
            ->toArray();
    }

    public function setPermissions($group_slug, ...$permissions)
    {
        $this->permissions()->sync($this->getPermissionIdsBySlug($group_slug, $permissions));
    }

    public function detachPermissions($group_slug, ...$permissions)
    {
        $this->permissions()->detach($this->getPermissionIdsBySlug($group_slug, $permissions));
    }
}
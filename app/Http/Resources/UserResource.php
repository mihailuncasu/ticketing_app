<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $default_data = parent::toArray($request);
        $roles = [];
        foreach ($this->roles as $role) {
            if ($role->group_slug === $request->group_slug) {
                $roles[] = RoleResource::make($role);
            }
        }

        $permissions = [];
        foreach ($this->permissions as $permission) {
            if ($permission->group_slug === $request->group_slug) {
                $permissions[] = $permission;
            }
        }

        $additional_data = [
            'roles' => $roles,
            'permissions' => PermissionResource::make($permissions),
            'avatar' => $this->getFirstMediaUrl('avatar', 'thumb')
        ];

        return array_merge($default_data, $additional_data);
    }
}
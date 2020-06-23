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
            $roles[] = RoleResource::make($role);
        }

        $additional_data = [
            'roles' => $roles,
            'permissions' => PermissionResource::make($this->permissions),
            'avatar' => $this->getFirstMediaUrl('avatar', 'thumb')
        ];

        return array_merge($default_data, $additional_data);
    }
}
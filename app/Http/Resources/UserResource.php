<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $defaultData = parent::toArray($request);

        $additionalData = [
          'role'=> RoleResource::make($this->roles->first()),
          'permissions'=> PermissionResource::make($this->permissions),
        ];

        return array_merge($defaultData, $additionalData);
    }
}
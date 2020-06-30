<?php

namespace App\Http\Resources;

use App\Role;
use App\User;
use Illuminate\Http\Resources\Json\JsonResource;

class GroupResource extends JsonResource
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
        $adminRole = $adminRole = Role::admin($this->slug)->first();

        // We only take the group managers;
        $users = [];
        foreach ($this->users as $user) {
            if ($user->hasRole($this->slug, $adminRole->slug)) {
                $users[] = $user->id;
            }
        }

        $additional_data = [
            'users' => UserResource::collection(User::whereIn('id', $users)->get())
        ];

        return array_merge($default_data, $additional_data);
    }
}
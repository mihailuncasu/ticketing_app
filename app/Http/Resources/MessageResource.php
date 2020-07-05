<?php

namespace App\Http\Resources;

use App\User;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
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
            'user' => UserResource::make(User::find($this->user_id)),
        ];

        return array_merge($defaultData, $additionalData);
    }
}

<?php

namespace App\Http\Resources;

use App\User;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $default_data = parent::toArray($request);
        $additional_data = [
            'assigned_to' => UserResource::make(User::find($this->assigned_to)),
            'created_by' => UserResource::make(User::find($this->created_by))
        ];

        return array_merge($default_data, $additional_data);
    }
}

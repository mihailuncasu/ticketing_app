<?php

namespace App\Http\Resources;

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

        $additional_data = [
            'users' => $this->users->all(),
        ];

        return array_merge($default_data, $additional_data);
    }
}

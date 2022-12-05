<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ActivityResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'action' => $this->action,
            'created_at' => $this->created_at,
            'space' => $this->whenLoaded('space', [
                'id' => $this->space->id,
                'name' => $this->space->name,
            ]),
        ];
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EarningResource extends JsonResource
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
            'id' => $this->id,
            'description' => $this->description,
            'amount' => formatCurrency($this->amount, $this->space->currency->iso),
            'when' => $this->when,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'category' => CategoryResource::make($this->whenLoaded('category')),
            'space' => SpaceResource::make($this->whenLoaded('space')),
            'recurring' => RecurringResource::make($this->whenLoaded('recurring')),
            'tags' => TagResource::collection($this->whenLoaded('tags')),
        ];
    }
}

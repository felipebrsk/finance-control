<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RecurringResource extends JsonResource
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
            'amount' => formatCurrency($this->amount, $this->currency->iso ?? $this->space->currency->iso),
            'type' => $this->type,
            'interval' => $this->interval,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'last_used_date' => $this->last_used_date,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'spendings' => SpendingResource::collection($this->whenLoaded('spendings')),
            'earnings' => EarningResource::collection($this->whenLoaded('earnings')),
            'tags' => TagResource::collection($this->whenLoaded('tags')),
            'currency' => CurrencyResource::make($this->whenLoaded('currency')),
            'space' => SpaceResource::make($this->whenLoaded('space')),
            'category' => CategoryResource::make($this->whenLoaded('category')),
            'tags' => TagResource::collection($this->whenLoaded('tags')),
        ];
    }
}

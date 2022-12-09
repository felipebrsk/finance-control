<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SpaceResource extends JsonResource
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
            'name' => $this->name,
            'slug' => $this->slug,
            'monthly_earning_recurrings' => formatCurrency($this->getMonthlyEarningRecurrings(), $this->currency->iso),
            'monthly_balance' => formatCurrency($this->getMonthlyBalance(), $this->currency->iso),
            'monthly_spending_recurrings' => formatCurrency($this->getMonthlySpendingRecurrings(), $this->currency->iso),
            'monthly_recurrings_calculated' => formatCurrency($this->calculateMonthlyRecurrings(), $this->currency->iso),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'user' => UserResource::make($this->whenLoaded('user')),
            'currency' => CurrencyResource::make($this->whenLoaded('currency')),
            'spendings' => SpendingResource::collection($this->whenLoaded('spendings')),
            'earnings' => EarningResource::collection($this->whenLoaded('earnings')),
            'recurrings' => RecurringResource::collection($this->whenLoaded('recurrings')),
            'tags' => TagResource::collection($this->whenLoaded('tags')),
            'categories' => CategoryResource::collection($this->whenLoaded('categories')),
            'activities' => ActivityResource::collection($this->whenLoaded('activities')),
        ];
    }
}

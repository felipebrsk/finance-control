<?php

namespace App\Http\Resources;

use Illuminate\Support\Str;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\{Category, Earning, Recurring, Spending};

class ActivityResource extends JsonResource
{
    /**
     * Map the activitable type.
     * 
     * @var array<string, string>
     */
    private const MAP_ACTIVITABLE_TYPE = [
        Spending::class => SpendingResource::class,
        Earning::class => EarningResource::class,
        Category::class => CategoryResource::class,
        Recurring::class => RecurringResource::class,
    ];

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $resourceClass = self::MAP_ACTIVITABLE_TYPE[$this->activitable_type];

        return [
            'action' => $this->action,
            'type' => strtolower(Str::afterLast($this->activitable_type, '\\')),
            'created_at' => $this->created_at,
            'activitable' => $resourceClass::make($this->whenLoaded('activitable')),
        ];
    }
}

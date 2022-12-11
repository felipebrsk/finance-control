<?php

namespace App\ModelFilters;

use Illuminate\Support\Carbon;
use EloquentFilter\ModelFilter;

class SpendingFilter extends ModelFilter
{
    /**
     * Filter by space.
     *
     * @param mixed $spaceId
     * @return void
     */
    public function space(mixed $spaceId): void
    {
        $this->whereSpaceId($spaceId);
    }

    /**
     * Filter by category.
     *
     * @param mixed $categoryId
     * @return void
     */
    public function category(mixed $categoryId): void
    {
        $this->whereCategoryId($categoryId);
    }

    /**
     * Filter by recurring.
     *
     * @param mixed $recurringId
     * @return void
     */
    public function recurring(mixed $recurringId): void
    {
        $this->whereRecurringId($recurringId);
    }

    /**
     * Filter by when.
     *
     * @param string $date
     * @return void
     */
    public function when(string $date): void
    {
        $date = Carbon::parse($date)->toDateString();

        $this->whereWhen($date);
    }

    /**
     * Filter by description.
     *
     * @param string $description
     * @return void
     */
    public function description(string $description): void
    {
        $this->where('description', 'LIKE', "%{$description}%");
    }
}

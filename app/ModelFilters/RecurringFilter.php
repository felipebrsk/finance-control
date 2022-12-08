<?php

namespace App\ModelFilters;

use EloquentFilter\ModelFilter;
use Illuminate\Support\Carbon;

class RecurringFilter extends ModelFilter
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
     * Filter by currency.
     * 
     * @param mixed $currencyId
     * @return void
     */
    public function currency(mixed $currencyId): void
    {
        $this->whereCurrencyId($currencyId);
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

    /**
     * Filter by start date.
     * 
     * @param string $startDate
     * @return void
     */
    public function start_date(string $startDate): void
    {
        $startDate = Carbon::parse($startDate)->toDateString();

        $this->whereStartDate($startDate);
    }

    /**
     * Filter by end date.
     * 
     * @param string $endDate
     * @return void
     */
    public function end_date(string $endDate): void
    {
        $endDate = Carbon::parse($endDate)->toDateString();

        $this->whereNotNull('end_date')->whereEndDate($endDate);
    }

    /**
     * Filter by type.
     * 
     * @param string $type
     * @return void
     */
    public function type(string $type): void
    {
        if (in_array($type, ['spending', 'earning'])) {
            $this->whereType($type);
        }
    }

    /**
     * Filter by interval.
     * 
     * @param string $interval
     * @return void
     */
    public function interval(string $interval): void
    {
        if (in_array($interval, [
            'yearly',
            'monthly',
            'biweekly',
            'weekly',
            'daily',
        ])) {
            $this->whereInterval($interval);
        }
    }
}

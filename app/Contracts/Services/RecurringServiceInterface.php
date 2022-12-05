<?php

namespace App\Contracts\Services;

use Illuminate\Database\Eloquent\Collection;

interface RecurringServiceInterface extends BasicServiceInterface
{
    /**
     * Get the yearly recurrings.
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getDueYearly(): Collection;

    /**
     * Get the monthly recurrings.
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getDueMonthly(): Collection;

    /**
     * Get the biweekly recurrings.
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getDueBiweekly(): Collection;

    /**
     * Get the weekly recurrings.
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getDueWeekly(): Collection;

    /**
     * Get the daily recurrings.
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getDueDaily(): Collection;

    /**
     * Get all user recurrings.
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllDueRecurrings(): Collection;
}

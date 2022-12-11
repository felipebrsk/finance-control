<?php

namespace App\Contracts\Services;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\{Collection, Model};
use Illuminate\Pagination\LengthAwarePaginator;

interface RecurringServiceInterface extends BasicServiceInterface, HasTagsServiceInterface
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

    /**
     * Get all recurrings with filter.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function allWithFilter(Request $request): LengthAwarePaginator;

    /**
     * Update from process recurrings job.
     *
     * @param array $data
     * @param mixed $id
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function updateFromJob(array $data, mixed $id): Model;
}

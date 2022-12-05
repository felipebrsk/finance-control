<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Collection;
use App\Contracts\Services\RecurringServiceInterface;
use App\Contracts\Repositories\RecurringRepositoryInterface;

class RecurringService extends AbstractService implements RecurringServiceInterface
{
    /**
     * The recurring repository interface.
     * 
     * @var \App\Contracts\Repositories\RecurringRepositoryInterface
     */
    protected $repository = RecurringRepositoryInterface::class;

    /**
     * Get the yearly recurrings.
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getDueYearly(): Collection
    {
        return $this->repository->getDueYearly();
    }

    /**
     * Get the monthly recurrings.
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getDueMonthly(): Collection
    {
        return $this->repository->getDueMonthly();
    }

    /**
     * Get the biweekly recurrings.
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getDueBiweekly(): Collection
    {
        return $this->repository->getDueBiweekly();
    }

    /**
     * Get the weekly recurrings.
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getDueWeekly(): Collection
    {
        return $this->repository->getDueWeekly();
    }

    /**
     * Get the daily recurrings.
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getDueDaily(): Collection
    {
        return $this->repository->getDueDaily();
    }

    /**
     * Get all user recurrings.
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllDueRecurrings(): Collection
    {
        return $this->getDueYearly()->merge(
            $this->getDueMonthly(),
            $this->getDueBiweekly(),
            $this->getDueWeekly(),
            $this->getDueDaily(),
        );
    }
}

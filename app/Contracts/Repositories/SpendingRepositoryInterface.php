<?php

namespace App\Contracts\Repositories;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

interface SpendingRepositoryInterface extends BasicRepositoryInterface, HasTagsRepositoryInterface
{
    /**
     * Get all auth spendings.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function allWithFilter(Request $request): LengthAwarePaginator;

    /**
     * Create from process recurrings job.
     *
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function createFromJob(array $data): Model;
}

<?php

namespace App\Contracts\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

interface EarningServiceInterface extends BasicServiceInterface, HasTagsServiceInterface
{
    /**
     * Get all auth earnings with space scope.
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

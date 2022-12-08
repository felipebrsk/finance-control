<?php

namespace App\Contracts\Repositories;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

interface EarningRepositoryInterface extends BasicRepositoryInterface
{

    /**
     * Get all auth earning with space scope.
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function allWithFilter(Request $request): LengthAwarePaginator;
}

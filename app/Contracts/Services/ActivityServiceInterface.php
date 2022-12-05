<?php

namespace App\Contracts\Services;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

interface ActivityServiceInterface extends BasicServiceInterface
{
    /**
     * Get all with filter.
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function allWithFilter(Request $request): LengthAwarePaginator;
}

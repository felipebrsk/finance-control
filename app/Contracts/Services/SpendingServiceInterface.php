<?php

namespace App\Contracts\Services;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

interface SpendingServiceInterface extends BasicServiceInterface, HasTagsServiceInterface
{
    /**
     * Get all auth spendings.
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function allWithFilter(Request $request): LengthAwarePaginator;
}

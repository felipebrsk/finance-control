<?php

namespace App\Contracts\Repositories;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

interface SpaceRepositoryInterface extends BasicRepositoryInterface
{
    /**
     * Get all auth user spaces.
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function allAuthUserSpacesWithFilter(Request $request): LengthAwarePaginator;
}

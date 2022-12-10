<?php

namespace App\Contracts\Services;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

interface SpaceServiceInterface extends BasicServiceInterface, HasTagsServiceInterface
{
    /**
     * Get all auth user spaces.
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function allAuthUserSpacesWithFilter(Request $request): LengthAwarePaginator;
}

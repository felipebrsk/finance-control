<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Contracts\Services\ActivityServiceInterface;
use App\Contracts\Repositories\ActivityRepositoryInterface;

class ActivityService extends AbstractService implements ActivityServiceInterface
{
    /**
     * The activity repository interface.
     *
     * @var \App\Contracts\Repositories\ActivityRepositoryInterface
     */
    protected $repository = ActivityRepositoryInterface::class;

    /**
     * Get all with filter.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function allWithFilter(Request $request): LengthAwarePaginator
    {
        return $this->repository->allWithFilter($request);
    }
}

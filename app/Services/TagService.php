<?php

namespace App\Services;

use App\Contracts\Repositories\TagRepositoryInterface;
use App\Contracts\Services\TagServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class TagService extends AbstractService implements TagServiceInterface
{
    /**
     * The tag repository interface.
     * 
     * @var \App\Contracts\Repositories\TagRepositoryInterface
     */
    protected $repository = TagRepositoryInterface::class;

    /**
     * Get all tags with filter.
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function allWithFilter(Request $request): LengthAwarePaginator
    {
        return $this->repository->allWithFilter($request);
    }
}

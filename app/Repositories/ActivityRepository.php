<?php

namespace App\Repositories;

use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Contracts\Repositories\ActivityRepositoryInterface;

class ActivityRepository extends AbstractRepository implements ActivityRepositoryInterface
{
    /**
     * The activity model.
     * 
     * @var \App\Models\Activity
     */
    protected $model = Activity::class;

    /**
     * Get all with filter.
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function allWithFilter(Request $request): LengthAwarePaginator
    {
        return $this->model::fromUserSpaces()->filter($request->all())->paginate(self::PER_PAGE);
    }
}

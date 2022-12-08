<?php

namespace App\Repositories;

use App\Models\Earning;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Contracts\Repositories\EarningRepositoryInterface;
use App\Exceptions\Earning\EarningDoesntBelongsToUserSpaceException;

class EarningRepository extends AbstractRepository implements EarningRepositoryInterface
{
    /**
     * The earning model.
     * 
     * @var \App\Models\Earning
     */
    protected $model = Earning::class;

    /**
     * Get all auth earning.
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function allWithFilter(Request $request): LengthAwarePaginator
    {
        return $this->model::fromUserSpaces()->filter($request->all())->paginate(self::PER_PAGE);
    }

    /**
     * Create a new earning.
     * 
     * @param array $data
     * @return \App\Models\Earning
     */
    public function create(array $data): Earning
    {
        $earning = $this->model::create($data);

        if ($tags = issetGetter('tags', $data)) {
            foreach ($tags as $tag) {
                $earning->tags()->syncWithoutDetaching($tag);
            }
        }

        return $earning;
    }

    /**
     * Find or fail an earning.
     * 
     * @param mixed $id
     * @return \App\Models\Earning
     */
    public function findOrFail(mixed $id): Earning
    {
        $earning = $this->model::findOrFail($id);

        if (Auth::user()->cant('view', $earning)) {
            throw new EarningDoesntBelongsToUserSpaceException();
        }

        return $earning;
    }

    /**
     * Update an earning.
     * 
     * @param array $data
     * @param mixed $id
     * @return \App\Models\Earning
     */
    public function update(array $data, mixed $id): Earning
    {
        $earning = $this->model::findOrFail($id);

        if (Auth::user()->cant('update', $earning)) {
            throw new EarningDoesntBelongsToUserSpaceException();
        }

        if ($tags = issetGetter('tags', $data)) {
            foreach ($tags as $tag) {
                $earning->tags()->syncWithoutDetaching($tag);
            }
        }

        $earning->update($data);

        return $earning;
    }

    /**
     * Delete an earning.
     * 
     * @param mixed $id
     * @return void
     */
    public function delete(mixed $id): void
    {
        $earning = $this->model::findOrFail($id);

        if (Auth::user()->cant('delete', $earning)) {
            throw new EarningDoesntBelongsToUserSpaceException();
        }

        $earning->delete();
    }
}

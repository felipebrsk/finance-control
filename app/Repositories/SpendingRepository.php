<?php

namespace App\Repositories;

use App\Models\Spending;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Contracts\Repositories\SpendingRepositoryInterface;
use App\Exceptions\Spending\SpendingDoesntBelongsToUserSpaceException;

class SpendingRepository extends AbstractRepository implements SpendingRepositoryInterface
{
    /**
     * The spending model.
     *
     * @var \App\Models\Spending
     */
    protected $model = Spending::class;

    /**
     * Get all auth spendings.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function allWithFilter(Request $request): LengthAwarePaginator
    {
        return $this->model::fromUserSpaces()->filter($request->all())->paginate(self::PER_PAGE);
    }

    /**
     * Create a new spending.
     *
     * @param array $data
     * @return \App\Models\Spending
     */
    public function create(array $data): Spending
    {
        $spending = $this->model::create($data);

        if ($tags = issetGetter('tags', $data)) {
            foreach ($tags as $tag) {
                $spending->tags()->syncWithoutDetaching($tag);
            }
        }

        return $spending;
    }

    /**
     * Create from process recurrings job.
     *
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function createFromJob(array $data): Spending
    {
        return $this->model::create($data);
    }

    /**
     * Find or fail a spending.
     *
     * @param mixed $id
     * @return \App\Models\Spending
     */
    public function findOrFail(mixed $id): Spending
    {
        $spending = $this->model::findOrFail($id);

        if (Auth::user()->cant('view', $spending)) {
            throw new SpendingDoesntBelongsToUserSpaceException();
        }

        return $spending;
    }

    /**
     * Update a spending.
     *
     * @param array $data
     * @param mixed $id
     * @return \App\Models\Spending
     */
    public function update(array $data, mixed $id): Spending
    {
        $spending = $this->model::findOrFail($id);

        if (Auth::user()->cant('update', $spending)) {
            throw new SpendingDoesntBelongsToUserSpaceException();
        }

        if ($tags = issetGetter('tags', $data)) {
            foreach ($tags as $tag) {
                $spending->tags()->syncWithoutDetaching($tag);
            }
        }

        $spending->update($data);

        return $spending;
    }

    /**
     * Delete a spending.
     *
     * @param mixed $id
     * @return void
     */
    public function delete(mixed $id): void
    {
        $spending = $this->model::findOrFail($id);

        if (Auth::user()->cant('delete', $spending)) {
            throw new SpendingDoesntBelongsToUserSpaceException();
        }

        $spending->delete();
    }

    /**
     * Detach a spending tags.
     *
     * @param array $ids
     * @param mixed $id
     * @return \App\Models\Spending
     */
    public function detachTags(array $ids, mixed $id): Spending
    {
        $spending = $this->model::findOrFail($id);

        if (Auth::user()->cant('update', $spending)) {
            throw new SpendingDoesntBelongsToUserSpaceException();
        }

        $spending->tags()->detach($ids);

        return $spending;
    }
}

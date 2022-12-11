<?php

namespace App\Repositories;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Contracts\Repositories\CategoryRepositoryInterface;
use App\Exceptions\Category\CategoryDoesntBelongsToUserSpaceException;

class CategoryRepository extends AbstractRepository implements CategoryRepositoryInterface
{
    /**
     * The category model.
     *
     * @var \App\Models\Category
     */
    protected $model = Category::class;

    /**
     * Get all with filter.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function allWithFilter(Request $request): LengthAwarePaginator
    {
        return $this->model::fromUserSpaces()->filter($request->all())->with('space')->paginate(self::PER_PAGE);
    }

    /**
     * Find or fail a category.
     *
     * @param mixed $id
     * @return \App\Models\Category
     */
    public function findOrFail(mixed $id): Category
    {
        $category = $this->model::findOrFail($id);

        if (Auth::user()->cant('view', $category)) {
            throw new CategoryDoesntBelongsToUserSpaceException();
        }

        return $category;
    }

    /**
     * Update a category.
     *
     * @param array $data
     * @param mixed $id
     * @return \App\Models\Category
     */
    public function update(array $data, mixed $id): Category
    {
        $category = $this->model::findOrFail($id);

        if (Auth::user()->cant('update', $category)) {
            throw new CategoryDoesntBelongsToUserSpaceException();
        }

        $category->update($data);

        return $category;
    }

    /**
     * Delete a category.
     *
     * @param mixed $id
     * @return void
     */
    public function delete(mixed $id): void
    {
        $category = $this->model::findOrFail($id);

        if (Auth::user()->cant('delete', $category)) {
            throw new CategoryDoesntBelongsToUserSpaceException();
        }

        $category->delete();
    }
}

<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Contracts\Repositories\CategoryRepositoryInterface;
use App\Exceptions\Space\SpaceDoesntBelongsToUserException;
use App\Contracts\Services\{SpaceServiceInterface, CategoryServiceInterface};

class CategoryService extends AbstractService implements CategoryServiceInterface
{
    /**
     * The category service interface.
     *
     * @var \App\Contracts\Repositories\CategoryRepositoryInterface
     */
    protected $repository = CategoryRepositoryInterface::class;

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

    /**
     * Create a new category.
     *
     * @param array $data
     * @return \App\Models\Category
     */
    public function create(array $data): Category
    {
        $this->assertCanCreate($data);

        return $this->repository->create($data);
    }

    /**
     * Assert can create the category.
     *
     * @param array $data
     * @throws \App\Exceptions\Space\SpaceDoesntBelongsToUserException
     * @return void
     */
    private function assertCanCreate(array $data): void
    {
        $spaceServiceInterface = resolve(SpaceServiceInterface::class);
        $associatingSpace = $spaceServiceInterface->findOrFail($data['space_id']);

        if ($associatingSpace->user->id !== Auth::id()) {
            throw new SpaceDoesntBelongsToUserException();
        }
    }
}

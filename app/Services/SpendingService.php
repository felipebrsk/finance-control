<?php

namespace App\Services;

use App\Models\Spending;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Contracts\Repositories\SpendingRepositoryInterface;
use App\Contracts\Services\{SpendingServiceInterface, CategoryServiceInterface};
use App\Exceptions\{
    Category\CategoryDoesntBelongsToUserSpaceException,
    Space\SpaceDoesntBelongsToUserException
};

class SpendingService extends AbstractService implements SpendingServiceInterface
{
    /**
     * The spending repository interface.
     * 
     * @var \App\Contracts\Repositories\SpendingRepositoryInterface
     */
    protected $repository = SpendingRepositoryInterface::class;

    /**
     * Get all auth spendings.
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function allWithFilter(Request $request): LengthAwarePaginator
    {
        return $this->repository->allWithFilter($request);
    }

    /**
     * Create a new spending.
     * 
     * @param array $data
     * @return \App\Models\Spending
     */
    public function create(array $data): Spending
    {
        $this->assertCanCreate($data);

        return $this->repository->create($data);
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
        $this->assertCanUpdate($data, $id);

        return $this->repository->update($data, $id);
    }

    /**
     * Assert can create a new spending.
     * 
     * @param array $data
     * @throws \App\Exceptions\Category\CategoryDoesntBelongsToUserSpaceException
     * @throws \App\Exceptions\Space\SpaceDoesntBelongsToUserException
     * @return void
     */
    private function assertCanCreate(array $data): void
    {
        $categoryServiceInterface = resolve(CategoryServiceInterface::class);

        $associatingCategory = $categoryServiceInterface->find($data['category_id']);

        if ($associatingCategory && $associatingCategory->space->user->id !== Auth::id()) {
            throw new CategoryDoesntBelongsToUserSpaceException();
        } else if (!Auth::user()->spaces->contains($data['space_id'])) {
            throw new SpaceDoesntBelongsToUserException();
        }
    }

    /**
     * Assert can update a spending.
     * 
     * @param array $data
     * @throws \App\Exceptions\Category\CategoryDoesntBelongsToUserSpaceException
     * @throws \App\Exceptions\Space\SpaceDoesntBelongsToUserException
     * @return void
     */
    private function assertCanUpdate(array $data): void
    {
        $categoryId = issetGetter('category_id', $data);
        $spaceId = issetGetter('space_id', $data);

        $categoryServiceInterface = resolve(CategoryServiceInterface::class);

        $associatingCategory = $categoryServiceInterface->find($categoryId);

        if ($associatingCategory && $associatingCategory->space->user->id !== Auth::id()) {
            throw new CategoryDoesntBelongsToUserSpaceException();
        } else if ($spaceId && !Auth::user()->spaces->contains($spaceId)) {
            throw new SpaceDoesntBelongsToUserException();
        }
    }
}

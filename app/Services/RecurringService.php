<?php

namespace App\Services;

use App\Models\Recurring;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Contracts\Repositories\RecurringRepositoryInterface;
use App\Exceptions\{
    Space\SpaceDoesntBelongsToUserException,
    Category\CategoryDoesntBelongsToUserSpaceException,
    Tag\TagDoesntBelongsToUserException
};
use App\Contracts\Services\{
    CategoryServiceInterface,
    SpaceServiceInterface,
    RecurringServiceInterface,
    TagServiceInterface
};

class RecurringService extends AbstractService implements RecurringServiceInterface
{
    /**
     * The recurring repository interface.
     *
     * @var \App\Contracts\Repositories\RecurringRepositoryInterface
     */
    protected $repository = RecurringRepositoryInterface::class;

    /**
     * Get all recurrings with filter.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function allWithFilter(Request $request): LengthAwarePaginator
    {
        return $this->repository->allWithFilter($request);
    }

    /**
     * Create a new recurring.
     *
     * @param array $data
     * @return \App\Models\Recurring
     */
    public function create(array $data): Recurring
    {
        $this->assertCanCreate($data);

        return $this->repository->create($data);
    }

    /**
     * Update a recurring.
     *
     * @param array $data
     * @param mixed $id
     * @return \App\Models\Recurring
     */
    public function update(array $data, mixed $id): Recurring
    {
        $this->assertCanUpdate($data, $id);

        return $this->repository->update($data, $id);
    }

    /**
     * Assert can create recurring.
     *
     * @param array $data
     * @throws \App\Exceptions\Category\CategoryDoesntBelongsToUserSpaceException
     * @throws \App\Exceptions\Space\SpaceDoesntBelongsToUserException
     * @return void
     */
    private function assertCanCreate(array $data): void
    {
        $spaceServiceInterface = resolve(SpaceServiceInterface::class);
        $categoryServiceInterface = resolve(CategoryServiceInterface::class);
        $tagServiceInterface = resolve(TagServiceInterface::class);

        $associatingSpace = $spaceServiceInterface->findOrFail($data['space_id']);

        if ($categoryId = issetGetter('category_id', $data)) {
            $associatingCategory = $categoryServiceInterface->findOrFail($categoryId);

            if ($associatingCategory->space->user->id !== Auth::id()) {
                throw new CategoryDoesntBelongsToUserSpaceException();
            }
        }

        if ($tagIds = issetGetter('tags', $data)) {
            $associatingTags = $tagServiceInterface->findIn($tagIds);

            foreach ($associatingTags as $tag) {
                if ($tag->user->id !== Auth::id()) {
                    throw new TagDoesntBelongsToUserException();
                }
            }
        }

        if ($associatingSpace->user->id !== Auth::id()) {
            throw new SpaceDoesntBelongsToUserException();
        }
    }

    /**
     * Assert can update recurring.
     *
     * @param array $data
     * @throws \App\Exceptions\Category\CategoryDoesntBelongsToUserSpaceException
     * @throws \App\Exceptions\Space\SpaceDoesntBelongsToUserException
     * @return void
     */
    private function assertCanUpdate(array $data): void
    {
        $spaceServiceInterface = resolve(SpaceServiceInterface::class);
        $categoryServiceInterface = resolve(CategoryServiceInterface::class);
        $tagServiceInterface = resolve(TagServiceInterface::class);

        if ($categoryId = issetGetter('category_id', $data)) {
            $associatingCategory = $categoryServiceInterface->findOrFail($categoryId);

            if ($associatingCategory->space->user->id !== Auth::id()) {
                throw new CategoryDoesntBelongsToUserSpaceException();
            }
        }

        if ($tagIds = issetGetter('tags', $data)) {
            $associatingTags = $tagServiceInterface->findIn($tagIds);

            foreach ($associatingTags as $tag) {
                if ($tag->user->id !== Auth::id()) {
                    throw new TagDoesntBelongsToUserException();
                }
            }
        }

        if ($spaceId = issetGetter('space_id', $data)) {
            $associatingSpace = $spaceServiceInterface->findOrFail($spaceId);

            if ($associatingSpace->user->id !== Auth::id()) {
                throw new SpaceDoesntBelongsToUserException();
            }
        }
    }

    /**
     * Get the yearly recurrings.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getDueYearly(): Collection
    {
        return $this->repository->getDueYearly();
    }

    /**
     * Get the monthly recurrings.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getDueMonthly(): Collection
    {
        return $this->repository->getDueMonthly();
    }

    /**
     * Get the biweekly recurrings.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getDueBiweekly(): Collection
    {
        return $this->repository->getDueBiweekly();
    }

    /**
     * Get the weekly recurrings.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getDueWeekly(): Collection
    {
        return $this->repository->getDueWeekly();
    }

    /**
     * Get the daily recurrings.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getDueDaily(): Collection
    {
        return $this->repository->getDueDaily();
    }

    /**
     * Get all user recurrings.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllDueRecurrings(): Collection
    {
        return $this->getDueYearly()->merge(
            $this->getDueMonthly(),
            $this->getDueBiweekly(),
            $this->getDueWeekly(),
            $this->getDueDaily(),
        );
    }

    /**
     * Detach recurring tags.
     *
     * @param array $ids
     * @param mixed $id
     * @return \App\Models\Recurring
     */
    public function detachTags(array $ids, mixed $id): Recurring
    {
        return $this->repository->detachTags($ids, $id);
    }

    /**
     * Update from process recurrings job.
     *
     * @param array $data
     * @param mixed $id
     * @return \App\Models\Recurring
     */
    public function updateFromJob(array $data, mixed $id): Recurring
    {
        return $this->repository->updateFromJob($data, $id);
    }
}

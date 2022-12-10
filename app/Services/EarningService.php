<?php

namespace App\Services;

use App\Models\Earning;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Contracts\Repositories\EarningRepositoryInterface;
use App\Contracts\Services\{
    EarningServiceInterface,
    CategoryServiceInterface,
    TagServiceInterface
};
use App\Exceptions\{
    Category\CategoryDoesntBelongsToUserSpaceException,
    Space\SpaceDoesntBelongsToUserException,
    Tag\TagDoesntBelongsToUserException
};

class EarningService extends AbstractService implements EarningServiceInterface
{
    /**
     * The earning repository interface.
     * 
     * @var \App\Contracts\Repositories\EarningRepositoryInterface
     */
    protected $repository = EarningRepositoryInterface::class;

    /**
     * Get all auth Earnings.
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function allWithFilter(Request $request): LengthAwarePaginator
    {
        return $this->repository->allWithFilter($request);
    }

    /**
     * Create a new earning.
     * 
     * @param array $data
     * @return \App\Models\Earning
     */
    public function create(array $data): Earning
    {
        $this->assertCanCreate($data);

        return $this->repository->create($data);
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
        $this->assertCanUpdate($data, $id);

        return $this->repository->update($data, $id);
    }

    /**
     * Assert can create a new Earning.
     * 
     * @param array $data
     * @throws \App\Exceptions\Tag\TagDoesntBelongsToUserException
     * @throws \App\Exceptions\Category\CategoryDoesntBelongsToUserSpaceException
     * @throws \App\Exceptions\Space\SpaceDoesntBelongsToUserException
     * @return void
     */
    private function assertCanCreate(array $data): void
    {
        $categoryServiceInterface = resolve(CategoryServiceInterface::class);
        $tagServiceInterface = resolve(TagServiceInterface::class);

        $associatingCategory = $categoryServiceInterface->find($data['category_id']);

        if ($tagIds = issetGetter('tags', $data)) {
            $associatingTags = $tagServiceInterface->findIn($tagIds);

            foreach ($associatingTags as $tag) {
                if ($tag->user->id !== Auth::id()) {
                    throw new TagDoesntBelongsToUserException();
                }
            }
        }

        if ($associatingCategory && $associatingCategory->space->user->id !== Auth::id()) {
            throw new CategoryDoesntBelongsToUserSpaceException();
        } else if (!Auth::user()->spaces->contains($data['space_id'])) {
            throw new SpaceDoesntBelongsToUserException();
        }
    }

    /**
     * Assert can update a Earning.
     * 
     * @param array $data
     * @throws \App\Exceptions\Tag\TagDoesntBelongsToUserException
     * @throws \App\Exceptions\Category\CategoryDoesntBelongsToUserSpaceException
     * @throws \App\Exceptions\Space\SpaceDoesntBelongsToUserException
     * @return void
     */
    private function assertCanUpdate(array $data): void
    {
        $categoryId = issetGetter('category_id', $data);
        $spaceId = issetGetter('space_id', $data);

        $categoryServiceInterface = resolve(CategoryServiceInterface::class);
        $tagServiceInterface = resolve(TagServiceInterface::class);

        $associatingCategory = $categoryServiceInterface->find($categoryId);

        if ($tagIds = issetGetter('tags', $data)) {
            $associatingTags = $tagServiceInterface->findIn($tagIds);

            foreach ($associatingTags as $tag) {
                if ($tag->user->id !== Auth::id()) {
                    throw new TagDoesntBelongsToUserException();
                }
            }
        }

        if ($associatingCategory && $associatingCategory->space->user->id !== Auth::id()) {
            throw new CategoryDoesntBelongsToUserSpaceException();
        } else if ($spaceId && !Auth::user()->spaces->contains($spaceId)) {
            throw new SpaceDoesntBelongsToUserException();
        }
    }

    /**
     * Detach earning tags.
     * 
     * @param array $ids
     * @param mixed $id
     * @return \App\Models\Earning
     */
    public function detachTags(array $ids, mixed $id): Earning
    {
        return $this->repository->detachTags($ids, $id);
    }

    /**
     * Create from process recurrings job.
     * 
     * @param array $data
     * @return \App\Models\Earning
     */
    public function createFromJob(array $data): Earning
    {
        return $this->repository->createFromJob($data);
    }
}

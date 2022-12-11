<?php

namespace App\Services;

use App\Models\Space;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Exceptions\Tag\TagDoesntBelongsToUserException;
use App\Contracts\Repositories\SpaceRepositoryInterface;
use App\Contracts\Services\{SpaceServiceInterface, TagServiceInterface};

class SpaceService extends AbstractService implements SpaceServiceInterface
{
    /**
     * The space repository interface.
     *
     * @var \App\Contracts\Repositories\SpaceRepositoryInterface
     */
    protected $repository = SpaceRepositoryInterface::class;

    /**
     * Get all auth user spaces.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function allAuthUserSpacesWithFilter(Request $request): LengthAwarePaginator
    {
        return $this->repository->allAuthUserSpacesWithFilter($request);
    }

    /**
     * Create a new space.
     *
     * @param array $data
     * @return \App\Models\Space
     */
    public function create(array $data): Space
    {
        $this->assertCanCreate($data);

        return $this->repository->create($data);
    }

    /**
     * Update an space.
     *
     * @param array $data
     * @param mixed $id
     * @return \App\Models\Space
     */
    public function update(array $data, mixed $id): Space
    {
        $this->assertCanUpdate($data, $id);

        return $this->repository->update($data, $id);
    }

    /**
     * Assert can create a new Earning.
     *
     * @param array $data
     * @throws \App\Exceptions\Tag\TagDoesntBelongsToUserException
     * @return void
     */
    private function assertCanCreate(array $data): void
    {
        $tagServiceInterface = resolve(TagServiceInterface::class);

        if ($tagIds = issetGetter('tags', $data)) {
            $associatingTags = $tagServiceInterface->findIn($tagIds);

            foreach ($associatingTags as $tag) {
                if ($tag->user->id !== Auth::id()) {
                    throw new TagDoesntBelongsToUserException();
                }
            }
        }
    }

    /**
     * Assert can update a Earning.
     *
     * @param array $data
     * @throws \App\Exceptions\Tag\TagDoesntBelongsToUserException
     * @return void
     */
    private function assertCanUpdate(array $data): void
    {
        $tagServiceInterface = resolve(TagServiceInterface::class);

        if ($tagIds = issetGetter('tags', $data)) {
            $associatingTags = $tagServiceInterface->findIn($tagIds);

            foreach ($associatingTags as $tag) {
                if ($tag->user->id !== Auth::id()) {
                    throw new TagDoesntBelongsToUserException();
                }
            }
        }
    }

    /**
     * Detach space tags.
     *
     * @param array $ids
     * @param mixed $id
     * @return \App\Models\Space
     */
    public function detachTags(array $ids, mixed $id): Space
    {
        return $this->repository->detachTags($ids, $id);
    }
}

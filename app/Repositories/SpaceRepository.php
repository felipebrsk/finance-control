<?php

namespace App\Repositories;

use App\Models\Space;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Contracts\Repositories\SpaceRepositoryInterface;
use App\Exceptions\Space\SpaceDoesntBelongsToUserException;

class SpaceRepository extends AbstractRepository implements SpaceRepositoryInterface
{
    /**
     * The space model.
     * 
     * @var \App\Models\Space
     */
    protected $model = Space::class;

    /**
     * Get all auth user spaces.
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function allAuthUserSpacesWithFilter(Request $request): LengthAwarePaginator
    {
        return $this->model::authUserSpaces()->filter($request->all())->paginate(self::PER_PAGE);
    }

    /**
     * Create a new space.
     * 
     * @param array $data
     * @return \App\Models\Space
     */
    public function create(array $data): Space
    {
        $data['user_id'] = Auth::id();

        $space = $this->model::create($data);

        if ($tags = issetGetter('tags', $data)) {
            foreach ($tags as $tag) {
                $space->tags()->syncWithoutDetaching($tag);
            }
        }

        return $space;
    }

    /**
     * Find or fail a space.
     * 
     * @param mixed $id
     * @return \App\Models\Space
     */
    public function findOrFail(mixed $id): Space
    {
        $space = $this->model::findOrFail($id);

        if (Auth::user()->cant('view', $space)) {
            throw new SpaceDoesntBelongsToUserException();
        }

        return $space;
    }

    /**
     * Update a space.
     * 
     * @param array $data
     * @param mixed $id
     * @return \App\Models\Space
     */
    public function update(array $data, mixed $id): Space
    {
        $space = $this->model::findOrFail($id);

        if ($tags = issetGetter('tags', $data)) {
            foreach ($tags as $tag) {
                $space->tags()->syncWithoutDetaching($tag);
            }
        }

        if (Auth::user()->cant('update', $space)) {
            throw new SpaceDoesntBelongsToUserException();
        }

        $space->update($data);

        return $space;
    }

    /**
     * Delete a space.
     * 
     * @param mixed $id
     * @return void
     */
    public function delete(mixed $id): void
    {
        $space = $this->model::findOrFail($id);

        if (Auth::user()->cant('delete', $space)) {
            throw new SpaceDoesntBelongsToUserException();
        }

        $space->delete();
    }

    /**
     * Detach a space tags.
     * 
     * @param array $ids
     * @param mixed $id
     * @return \App\Models\Space
     */
    public function detachTags(array $ids, mixed $id): Space
    {
        $space = $this->model::findOrFail($id);

        if (Auth::user()->cant('update', $space)) {
            throw new SpaceDoesntBelongsToUserException();
        }

        $space->tags()->detach($ids);

        return $space;
    }
}

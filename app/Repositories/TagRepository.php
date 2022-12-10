<?php

namespace App\Repositories;

use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Contracts\Repositories\TagRepositoryInterface;
use App\Exceptions\Tag\TagDoesntBelongsToUserException;

class TagRepository extends AbstractRepository implements TagRepositoryInterface
{
    /**
     * The tag model.
     * 
     * @var \App\Models\Tag
     */
    protected $model = Tag::class;

    /**
     * Get all tags with filter.
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function allWithFilter(Request $request): LengthAwarePaginator
    {
        return $this->model::fromAuthUser()->filter($request->all())->paginate(self::PER_PAGE);
    }

    /**
     * Find or fail a tag.
     * 
     * @param mixed $id
     * @return \App\Models\Tag
     */
    public function findOrFail(mixed $id): Tag
    {
        $tag = $this->model::findOrFail($id);

        if (Auth::user()->cant('view', $tag)) {
            throw new TagDoesntBelongsToUserException();
        }

        return $tag;
    }

    /**
     * Create a new tag.
     * 
     * @param array $data
     * @return \App\Models\Tag
     */
    public function create(array $data): Tag
    {
        $data['user_id'] = Auth::id();

        return $this->model::create($data);
    }

    /**
     * Update a tag.
     * 
     * @param array $data
     * @param mixed $id
     * @return \App\Models\Tag
     */
    public function update(array $data, mixed $id): Tag
    {
        $tag = $this->findOrFail($id);

        if (Auth::user()->cant('update', $tag)) {
            throw new TagDoesntBelongsToUserException();
        }

        $tag->update($data);

        return $tag;
    }
}

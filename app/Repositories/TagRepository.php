<?php

namespace App\Repositories;

use App\Contracts\Repositories\TagRepositoryInterface;
use App\Models\Tag;

class TagRepository extends AbstractRepository implements TagRepositoryInterface
{
    /**
     * The tag model.
     * 
     * @var \App\Models\Tag
     */
    protected $model = Tag::class;
}

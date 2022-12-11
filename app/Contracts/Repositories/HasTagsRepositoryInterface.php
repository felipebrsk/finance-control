<?php

namespace App\Contracts\Repositories;

use Illuminate\Database\Eloquent\Model;

interface HasTagsRepositoryInterface
{
    /**
     * Detach a space tags.
     *
     * @param array $ids
     * @param mixed $id
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function detachTags(array $ids, mixed $id): Model;
}

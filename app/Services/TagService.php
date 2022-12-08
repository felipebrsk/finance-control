<?php

namespace App\Services;

use App\Contracts\Repositories\TagRepositoryInterface;
use App\Contracts\Services\TagServiceInterface;

class TagService extends AbstractService implements TagServiceInterface
{
    /**
     * The tag repository interface.
     * 
     * @var \App\Contracts\Repositories\TagRepositoryInterface
     */
    protected $repository = TagRepositoryInterface::class;
}

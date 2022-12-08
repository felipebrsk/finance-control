<?php

namespace App\Services;

use App\Contracts\Services\CategoryServiceInterface;
use App\Contracts\Repositories\CategoryRepositoryInterface;

class CategoryService extends AbstractService implements CategoryServiceInterface
{
    /**
     * The category service interface.
     * 
     * @var \App\Contracts\Repositories\CategoryRepositoryInterface
     */
    protected $repository = CategoryRepositoryInterface::class;
}

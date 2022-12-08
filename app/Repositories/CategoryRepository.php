<?php

namespace App\Repositories;

use App\Models\Category;
use App\Contracts\Repositories\CategoryRepositoryInterface;

class CategoryRepository extends AbstractRepository implements CategoryRepositoryInterface
{
    /**
     * The category model.
     * 
     * @var \App\Models\Category
     */
    protected $model = Category::class;
}

<?php

namespace App\Repositories;

use App\Contracts\Repositories\SpendingRepositoryInterface;
use App\Models\Spending;

class SpendingRepository extends AbstractRepository implements SpendingRepositoryInterface
{
    /**
     * The spending model.
     * 
     * @var \App\Models\Spending
     */
    protected $model = Spending::class;
}

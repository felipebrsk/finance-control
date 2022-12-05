<?php

namespace App\Repositories;

use App\Contracts\Repositories\EarningRepositoryInterface;
use App\Models\Earning;

class EarningRepository extends AbstractRepository implements EarningRepositoryInterface
{
    /**
     * The earning model.
     * 
     * @var \App\Models\Earning
     */
    protected $model = Earning::class;
}

<?php

namespace App\Services;

use App\Contracts\Repositories\EarningRepositoryInterface;
use App\Contracts\Services\EarningServiceInterface;

class EarningService extends AbstractService implements EarningServiceInterface
{
    /**
     * The earning repository interface.
     * 
     * @var \App\Contracts\Repositories\EarningRepositoryInterface
     */
    protected $repository = EarningRepositoryInterface::class;
}

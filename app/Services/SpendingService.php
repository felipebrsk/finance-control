<?php

namespace App\Services;

use App\Contracts\Repositories\SpendingRepositoryInterface;
use App\Contracts\Services\SpendingServiceInterface;

class SpendingService extends AbstractService implements SpendingServiceInterface
{
    /**
     * The spending repository interface.
     * 
     * @var \App\Contracts\Repositories\SpendingRepositoryInterface
     */
    protected $repository = SpendingRepositoryInterface::class;
}

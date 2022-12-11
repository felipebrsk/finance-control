<?php

namespace App\Services;

use App\Contracts\Services\CurrencyServiceInterface;
use App\Contracts\Repositories\CurrencyRepositoryInterface;

class CurrencyService extends AbstractService implements CurrencyServiceInterface
{
    /**
     * The currency repository interface.
     *
     * @var \App\Contracts\Repositories\CurrencyRepositoryInterface
     */
    protected $repository = CurrencyRepositoryInterface::class;

    /**
     * Get converted value.
     *
     * @param string $baseCurrencyIso
     * @param string $targetCurrencyIso
     * @param int $amount
     * @return int
     */
    public function convert(string $baseCurrencyIso, string $targetCurrencyIso, int $amount): int
    {
        return $this->repository->convert($baseCurrencyIso, $targetCurrencyIso, $amount);
    }
}

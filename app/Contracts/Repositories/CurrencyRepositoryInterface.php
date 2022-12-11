<?php

namespace App\Contracts\Repositories;

interface CurrencyRepositoryInterface extends BasicRepositoryInterface
{
    /**
     * Get converted value.
     *
     * @param string $baseCurrencyIso
     * @param string $targetCurrencyIso
     * @param int $amount
     * @return int
     */
    public function convert(string $baseCurrencyIso, string $targetCurrencyIso, int $amount): int;
}

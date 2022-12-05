<?php

namespace App\Contracts\Services;

interface CurrencyServiceInterface extends BasicServiceInterface
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

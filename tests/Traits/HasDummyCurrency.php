<?php

namespace Tests\Traits;

use App\Models\Currency;
use Illuminate\Database\Eloquent\Collection;

trait HasDummyCurrency
{
    /**
     * Create dummy currency.
     * 
     * @param array $data
     * @return \App\Models\Currency
     */
    public function createDummyCurrency(array $data = []): Currency
    {
        return Currency::factory()->create($data);
    }

    /**
     * Create dummy currencies.
     * 
     * @param int $times
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function createDummyCurrencies(int $times, array $data = []): Collection
    {
        return Currency::factory($times)->create($data);
    }
}

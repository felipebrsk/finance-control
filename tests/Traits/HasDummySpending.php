<?php

namespace Tests\Traits;

use App\Models\Spending;
use Illuminate\Database\Eloquent\Collection;

trait HasDummySpending
{
    /**
     * Create dummy spending.
     * 
     * @param array $data
     * @return \App\Models\Spending
     */
    public function createDummySpending(array $data = []): Spending
    {
        return Spending::factory()->create($data);
    }

    /**
     * Create dummy spendings.
     * 
     * @param int $times
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function createDummySpendings(int $times, array $data = []): Collection
    {
        return Spending::factory($times)->create($data);
    }
}

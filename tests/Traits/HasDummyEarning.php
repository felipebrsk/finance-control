<?php

namespace Tests\Traits;

use App\Models\Earning;
use Illuminate\Database\Eloquent\Collection;

trait HasDummyEarning
{
    /**
     * Create dummy earning.
     * 
     * @param array $data
     * @return \App\Models\Earning
     */
    public function createDummyEarning(array $data = []): Earning
    {
        return Earning::factory()->create($data);
    }

    /**
     * Create dummy earnings.
     * 
     * @param int $times
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function createDummyEarnings(int $times, array $data = []): Collection
    {
        return Earning::factory($times)->create($data);
    }
}

<?php

namespace Tests\Traits;

use App\Models\{Space, Recurring};
use Illuminate\Database\Eloquent\Collection;

trait HasDummyRecurring
{
    /**
     * Create dummy recurring.
     *
     * @param array $data
     * @return \App\Models\Recurring
     */
    public function createDummyRecurring(array $data = []): Recurring
    {
        return Recurring::factory()->create($data);
    }

    /**
     * Create dummy recurrings.
     *
     * @param int $times
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function createDummyRecurrings(int $times, array $data = []): Collection
    {
        return Recurring::factory($times)->create($data);
    }

    /**
     * Create dummy recurring to.
     *
     * @param \App\Models\Space $space
     * @param array $data
     * @return \App\Models\Recurring
     */
    public function createDummyRecurringTo(Space $space, array $data = []): Recurring
    {
        $recurring = $this->createDummyRecurring($data);

        $recurring->space()->associate($space)->save();

        return $recurring;
    }
}

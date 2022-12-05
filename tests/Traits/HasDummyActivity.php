<?php

namespace Tests\Traits;

use App\Models\{Activity, Space};
use Illuminate\Database\Eloquent\Collection;

trait HasDummyActivity
{
    /**
     * Create dummy activity.
     * 
     * @param array $data
     * @return \App\Models\Activity
     */
    public function createDummyActivity(array $data = []): Activity
    {
        return Activity::factory()->create($data);
    }

    /**
     * Create dummy activities.
     * 
     * @param int $times
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function createDummyActivities(int $times, array $data = []): Collection
    {
        return Activity::factory($times)->create($data);
    }

    /**
     * Create dummy activity to.
     * 
     * @param \App\Models\Space $space
     * @param array $data
     * @return \App\Models\Activity
     */
    public function createDummyActivityTo(Space $space, array $data = []): Activity
    {
        $activity = $this->createDummyActivity($data);

        $activity->space()->associate($space)->save();

        return $activity;
    }
}

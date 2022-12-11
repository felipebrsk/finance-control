<?php

namespace Tests\Traits;

use App\Models\{User, Space};
use Illuminate\Database\Eloquent\Collection;

trait HasDummySpace
{
    /**
     * Create dummy space.
     *
     * @param array $data
     * @return \App\Models\Space
     */
    public function createDummySpace(array $data = []): Space
    {
        return Space::factory()->create($data);
    }

    /**
     * Create dummy spaces.
     *
     * @param int $times
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function createDummySpaces(int $times, array $data = []): Collection
    {
        return Space::factory($times)->create($data);
    }

    /**
     * Create dummy space to.
     *
     * @param \App\Models\User $user
     * @param array $data
     * @return \App\Models\Space
     */
    public function createDummySpaceTo(User $user, array $data = []): Space
    {
        $space = $this->createDummySpace($data);

        $space->user()->associate($user)->save();

        return $space;
    }
}

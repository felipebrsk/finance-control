<?php

namespace Tests\Traits;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

trait HasDummyUser
{
    /**
     * Create dummy user.
     * 
     * @param array $data
     * @return \App\Models\User
     */
    public function createDummyUser(array $data = []): User
    {
        return User::factory()->create($data);
    }

    /**
     * Create dummy users.
     * 
     * @param int $times
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function createDummyUsers(int $times, array $data = []): Collection
    {
        return User::factory($times)->create($data);
    }

    /**
     * Acting as dummy user.
     * 
     * @param array $data
     * @return \App\Models\User
     */
    public function actingAsDummyUser(array $data = []): User
    {
        $user = $this->createDummyUser();

        $this->actingAs($user);

        return $user;
    }
}

<?php

namespace App\Events\User;

use App\Models\{User, Activity};
use App\Traits\HasBroadcastActivity;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class UserPasswordUpdated implements ShouldBroadcast
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;
    use HasBroadcastActivity;

    /**
     * Create a new event instance.
     *
     * @param \App\Models\User $user
     * @return void
     */
    public function __construct(User $user)
    {
        $this->activity = Activity::create([
            'activitable_id' => $user->id,
            'activitable_type' => $user::class,
            'action' => 'password.changed',
        ]);

        $this->broadcastChannel = 'users';
    }
}

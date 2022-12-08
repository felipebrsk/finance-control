<?php

namespace App\Events\Recurring;

use Illuminate\Broadcasting\Channel;
use App\Models\{Activity, Recurring};
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class RecurringUpdated
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @param \App\Models\Recurring $recurring
     * @return void
     */
    public function __construct(Recurring $recurring)
    {
        Activity::create([
            'activitable_id' => $recurring->id,
            'activitable_type' => $recurring::class,
            'space_id' => $recurring->space->id,
            'action' => 'recurring.updated',
        ]);
    }

    # TODO implement with pusher.
    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    // public function broadcastOn()
    // {
    //     return new PrivateChannel('channel-name');
    // }
}
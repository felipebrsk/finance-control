<?php

namespace App\Events\Recurring;

use App\Traits\HasBroadcastActivity;
use App\Models\{Activity, Recurring};
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class RecurringCreated implements ShouldBroadcast
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;
    use HasBroadcastActivity;

    /**
     * Create a new event instance.
     *
     * @param \App\Models\Recurring $recurring
     * @return void
     */
    public function __construct(Recurring $recurring)
    {
        $this->activity = Activity::create([
            'activitable_id' => $recurring->id,
            'activitable_type' => $recurring::class,
            'space_id' => $recurring->space->id,
            'action' => 'recurring.created',
        ]);

        $this->broadcastChannel = 'recurrings';
    }
}

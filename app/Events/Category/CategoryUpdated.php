<?php

namespace App\Events\Category;

use App\Models\{Activity, Category};
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class CategoryUpdated
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
    public function __construct(Category $category)
    {
        Activity::create([
            'activitable_id' => $category->id,
            'activitable_type' => $category::class,
            'space_id' => $category->space->id,
            'action' => 'category.updated',
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

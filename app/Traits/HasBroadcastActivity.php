<?php

namespace App\Traits;

use Illuminate\Broadcasting\PrivateChannel;

trait HasBroadcastActivity
{
    /**
     * The created activity.
     *
     * @var \App\Models\Activity
     */
    protected $activity;

    /**
     * The channel to be dispatched the event.
     *
     * @var string
     */
    protected $broadcastChannel;

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel($this->broadcastChannel);
    }

    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith(): array
    {
        return ['activity' => $this->activity];
    }

    /**
     * The event's broadcast name.
     *
     * @return string
     */
    public function broadcastAs(): string
    {
        return $this->activity->action;
    }
}

<?php

namespace App\Events\Category;

use App\Models\{Activity, Category};
use App\Traits\HasBroadcastActivity;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class CategoryCreated implements ShouldBroadcast
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;
    use HasBroadcastActivity;

    /**
     * Create a new event instance.
     *
     * @param \App\Models\Category $category
     * @return void
     */
    public function __construct(Category $category)
    {
        $this->activity = Activity::create([
            'activitable_id' => $category->id,
            'activitable_type' => $category::class,
            'space_id' => $category->space->id,
            'action' => 'category.created',
        ]);

        $this->broadcastChannel = 'categories';
    }
}

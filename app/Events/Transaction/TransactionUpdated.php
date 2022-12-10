<?php

namespace App\Events\Transaction;

use App\Models\Activity;
use App\Traits\HasBroadcastActivity;
use Illuminate\Queue\SerializesModels;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class TransactionUpdated implements ShouldBroadcast
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;
    use HasBroadcastActivity;

    /**
     * Create a new event instance.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @return void
     */
    public function __construct(Model $model)
    {
        $this->activity = Activity::create([
            'activitable_id' => $model->id,
            'activitable_type' => $model::class,
            'space_id' => $model->space->id,
            'action' => 'transaction.updated',
        ]);

        $this->broadcastChannel = 'transactions';
    }
}

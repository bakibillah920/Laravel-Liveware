<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ShoutEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $shout;


    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($shout)
    {
        $this->shout = $shout;
    }


    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('messageShoutBox');
    }

    public function broadcastWith()
    {
        return [
            'comment' => $this->shout->comment,
            'user_id' => $this->shout->user_id,
            'created_at' => $this->shout->created_at->toFormattedDateString(),
        ];
    }

    public function broadcastAs()
    {
        return 'shout.created';
    }

}

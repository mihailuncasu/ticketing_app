<?php

namespace App\Events;

use Hyn\Tenancy\Contracts\CurrentHostname;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Support\Str;

class GroupCreatedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $group;

    /**
     * Create a new event instance.
     *
     * @param $group
     */
    public function __construct($group)
    {
        $this->group = $group;
    }

    public function broadcastOn()
    {
    }
}

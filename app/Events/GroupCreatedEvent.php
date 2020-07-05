<?php

namespace App\Events;

use Hyn\Tenancy\Contracts\CurrentHostname;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Support\Str;

class GroupCreatedEvent implements ShouldBroadcast
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
        $hostname = Str::before(app(CurrentHostname::class)->fqdn, '.' . env("TENANT_URL_BASE"));
        return $hostname . '.group-event';
    }
}

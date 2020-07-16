<?php

namespace App\Events;

use App\Group;
use App\Http\Resources\MessageResource;
use Hyn\Tenancy\Contracts\CurrentHostname;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Support\Str;

class MessageSentEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($message)
    {
        //
        $this->message = MessageResource::make($message);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        $slug = Group::find($this->message->group_id)->slug;
        $hostname = Str::before(app(CurrentHostname::class)->fqdn, '.' . env("TENANT_URL_BASE"));
        return new PrivateChannel("$hostname.$slug.chat");
    }
}

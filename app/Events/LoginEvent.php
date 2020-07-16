<?php

namespace App\Events;

use App\Http\Resources\UserResource;
use App\User;
use Hyn\Tenancy\Contracts\CurrentHostname;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Support\Str;

class LoginEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var User
     */
    public $user;

    /**
     * Create a new event instance.
     *
     * @param User $user
     */
    public function __construct(User $user)
    {
        $user->update([
            'status' => 'online'
        ]);
        $this->user = UserResource::make($user);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return string
     */
    public function broadcastOn()
    {

        $hostname = Str::before(app(CurrentHostname::class)->fqdn, '.' . env("TENANT_URL_BASE"));
        return new PrivateChannel("$hostname.login-event");
    }
}

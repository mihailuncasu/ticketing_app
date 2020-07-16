<?php

namespace App\Listeners;

use App\Events\UserCreatedEvent;
use App\Group;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;

class AttachNewUserToGroup
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  UserCreatedEvent  $event
     * @return void
     */
    public function handle(UserCreatedEvent $event)
    {
        $group = Group::where('slug', $event->group_slug)->first();
        $group->users()->attach([$event->user->id => ['added_by' => Auth::user()->id]]);

    }
}

<?php

namespace App\Listeners;

use App\Events\GroupCreatedEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateRolesAndPermissionsListener
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
     * @param  GroupCreatedEvent  $event
     * @return void
     */
    public function handle(GroupCreatedEvent $event)
    {
        //
    }
}

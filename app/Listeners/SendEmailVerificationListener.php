<?php

namespace App\Listeners;

use App\Events\AdminHasRegisteredEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendEmailVerificationListener
{
    /**
     * Handle the event.
     *
     * @param  AdminHasRegisteredEvent  $event
     * @return void
     */
    public function handle(AdminHasRegisteredEvent $event)
    {
        $event->user->sendEmailVerificationNotification();
    }
}

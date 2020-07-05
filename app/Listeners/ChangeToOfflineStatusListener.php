<?php

namespace App\Listeners;

use App\Events\LogoutEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ChangeToOfflineStatusListener
{
    /**
     * Handle the event.
     *
     * @param  LogoutEvent  $event
     * @return void
     */
    public function handle(LogoutEvent $event)
    {
        $event->user->update([
            'status' => 'offline'
        ]);
    }
}

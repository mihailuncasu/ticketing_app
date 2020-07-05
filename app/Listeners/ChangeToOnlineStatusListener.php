<?php

namespace App\Listeners;

use App\Events\LoginEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ChangeToOnlineStatusListener
{
    /**
     * Handle the event.
     *
     * @param  LoginEvent  $event
     * @return void
     */
    public function handle(LoginEvent $event)
    {
        $event->user->update([
            'status' => 'online'
        ]);
    }
}

<?php

namespace App\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class AttachNewUserMediaListener
{
    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $avatar_url = 'https://img.favpng.com/2/12/12/computer-icons-portable-network-graphics-user-profile-avatar-png-favpng-L1ihcbxsHbnBKBvjjfBMFGbb7.jpg';
        $event->user->addMediaFromUrl($avatar_url)->toMediaCollection('avatar');
    }
}

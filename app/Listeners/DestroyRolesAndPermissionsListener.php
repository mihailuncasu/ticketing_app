<?php

namespace App\Listeners;

use App\Events\GroupDestroyedEvent;
use App\Permission;
use App\Role;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class DestroyRolesAndPermissionsListener
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
     * @param  GroupDestroyedEvent  $event
     * @return void
     */
    public function handle(GroupDestroyedEvent $event)
    {
        $groupSlug = $event->group->slug;
        Permission::where('group_slug', $groupSlug)->delete();
        Role::where('group_slug', $groupSlug)->delete();
    }
}

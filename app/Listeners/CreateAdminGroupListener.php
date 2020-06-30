<?php

namespace App\Listeners;

use App\Events\AdminGroupCreatedEvent;
use App\Group;
use App\Role;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateAdminGroupListener
{
    /**
     * Handle the event.
     *
     * @param object $event
     * @return void
     */
    public function handle($event)
    {
        // Create the group;
        $group = Group::create([
            'name' => ucwords('company'),
            'description' => 'The big company group',
            'created_by' => $event->user->id,
        ]);

        event(new AdminGroupCreatedEvent($group));

        $adminRole = Role::admin($group->slug)->first();
        $memberRole = Role::member($group->slug)->first();

        $event->user->roles()->attach([$adminRole->id, $memberRole->id]);

        $group->users()->attach($event->user->id, ['added_by' => $group->created_by]);
    }
}

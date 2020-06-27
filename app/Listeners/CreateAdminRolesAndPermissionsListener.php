<?php

namespace App\Listeners;

use App\Events\AdminGroupCreatedEvent;
use App\Permission;
use App\Role;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Str;

class CreateAdminRolesAndPermissionsListener
{
    /**
     * Handle the event.
     *
     * @param AdminGroupCreatedEvent $event
     * @return void
     */
    public function handle(AdminGroupCreatedEvent $event)
    {
        // Create permissions for an admin
        $adminPermissions = collect([
            'view users dashboard',
            'view roles dashboard',
            'view permissions dashboard',
            'view groups dashboard',
            'create user',
            'edit user',
            'delete user',
            'create role',
            'edit role',
            'delete role',
            'create group',
            'edit group',
            'delete group',
        ])->map(function ($name) use ($event) {
            return Permission::create([
                'name' => $name,
                'display_name' => Str::title($name),
                'group_slug' => $event->group->slug
            ]);
        });

        // Add admin role
        $adminRole = Role::create([
            'name' => 'admin',
            'display_name' => 'Admin',
            'group_slug' => $event->group->slug
        ]);

        $adminRole->givePermissionsTo($adminPermissions->pluck('id')->toArray());
    }
}

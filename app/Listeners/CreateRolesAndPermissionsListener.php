<?php

namespace App\Listeners;

use App\Events\GroupCreatedEvent;
use App\Permission;
use App\Role;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Str;

class CreateRolesAndPermissionsListener
{
    /**
     * Handle the event.
     *
     * @param GroupCreatedEvent $event
     * @return void
     */
    public function handle(GroupCreatedEvent $event)
    {
        $groupAdminPermissions = collect([
            'view roles dashboard',
            'view permissions dashboard',
            'add member',
            'edit member',
            'remove member',
            'create role',
            'edit role',
            'delete role',
            'edit group',
            'delete group',
            'edit permission',
            'delete permission',
        ])->map(function ($name) use ($event) {
            return Permission::create([
                'name' => $name,
                'display_name' => Str::title($name),
                'group_slug' => $event->group->slug
            ]);
        });

        $groupMemberPermissions = collect([
            'view group chat dashboard',
            'view members dashboard',
            'view tickets dashboard',
            'view news dashboard',
        ])->map(function ($name) use ($event) {
            return Permission::create([
                'name' => $name,
                'display_name' => Str::title($name),
                'group_slug' => $event->group->slug
            ]);
        });

        $groupAdmin = Role::create([
            'name' => 'admin',
            'display_name' => 'Admin',
            'group_slug' => $event->group->slug
        ]);

        $groupMember = Role::create([
            'name' => 'member',
            'display_name' => 'Member',
            'group_slug' => $event->group->slug
        ]);

        $groupAdmin->givePermissionsTo($groupAdminPermissions->pluck('id')->toArray());
        $groupAdmin->givePermissionsTo($groupMemberPermissions->pluck('id')->toArray());
        $groupMember->givePermissionsTo($groupMemberPermissions->pluck('id')->toArray());
    }
}

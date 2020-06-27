<?php

namespace App\Providers;

use App\Events\AdminGroupCreatedEvent;
use App\Events\AdminHasRegisteredEvent;
use App\Events\GroupCreatedEvent;
use App\Events\UserCreatedEvent;
use Hyn\Tenancy\Events\Websites\Switched;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        AdminHasRegisteredEvent::class => [
            \App\Listeners\CreateAdminGroupListener::class,
            \App\Listeners\AttachNewUserMediaListener::class,
            \App\Listeners\SendEmailVerificationListener::class
        ],

        AdminGroupCreatedEvent::class => [
            \App\Listeners\CreateAdminRolesAndPermissionsListener::class,
        ],

        UserCreatedEvent::class => [
            \App\Listeners\AttachNewUserMediaListener::class,
        ],

        GroupCreatedEvent::class => [
            \App\Listeners\CreateRolesAndPermissionsListener::class,
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        Event::listen(Switched::class, function (Switched $event) {
            config([
                'filesystems.disks.tenant' =>
                    [
                        'driver' => 'local',
                        'root' => storage_path() . '/app/tenancy/tenants/' . $event->website->uuid,
                        'url' => config('app.url') . '/storage/tenancy/tenants/' . $event->website->uuid,
                        'visibility' => 'public',
                    ]
            ]);
        });
    }
}

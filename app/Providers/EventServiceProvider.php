<?php

namespace App\Providers;

use Hyn\Tenancy\Events\Websites\Switched;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
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

<?php

namespace App\Providers;

use App\Permission;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class PermissionsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $permissions = Permission::all();

        foreach ($permissions as $permission) {
            $slug = $permission->slug;
            Gate::define($slug, function ($user, $groupSlug) use ($slug){
                return $user->hasPermissionTo($groupSlug, $slug);
            });
        }
    }
}

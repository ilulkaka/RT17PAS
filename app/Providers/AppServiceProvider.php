<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
// use App\Models\Sanctum\PersonalAccessToken;
// use Laravel\Sanctum\Sanctum;
use Illuminate\Support\Facades\Event;
use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    public function boot(): void
    {

        Gate::define('role-admin', function (User $user) {
            return $user->hasRole('admin');
        });

        Gate::define('sect-pengurus', function (User $user) {
            return $user->departments->contains('section', 'PENGURUS');
        });
        

    }
}

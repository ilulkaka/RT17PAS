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

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('role-admin', function (User $user) {
            return $user->hasRole('admin');
        });

        Gate::define('dept-maintenance', function (User $user) {
            return $user->departments->contains('name', 'MAINTENANCE');
        });
        Gate::define('dept-tooling', function (User $user) {
            return $user->departments->contains('name', 'TOOLING');
        });
        Gate::define('dept-qa', function (User $user) {
            return $user->departments->contains('name', 'QA');
        });
        Gate::define('dept-inspection', function (User $user) {
            return $user->departments->contains('name', 'INSPECTION');
        });
        Gate::define('dept-ppic', function (User $user) {
            return $user->departments->contains('name', 'PPIC');
        });
        Gate::define('dept-exim', function (User $user) {
            return $user->departments->contains('name', 'EXIM');
        });
        Gate::define('dept-hse', function (User $user) {
            return $user->departments->contains('name', 'HSE');
        });
        Gate::define('dept-iso', function (User $user) {
            return $user->departments->contains('name', 'ISO');
        });
        Gate::define('dept-pga', function (User $user) {
            return $user->departments->contains('name', 'PGA');
        });
        Gate::define('dept-purchasing', function (User $user) {
            return $user->departments->contains('name', 'PURCHASING');
        });
        Gate::define('dept-manager', function (User $user) {
            return $user->departments->contains('name', 'MANAGER');
        });
        // Gate::define('check-ability', function (User $user, $ability) {
        //     dd(22);
        //     return $user->hasPermissionTo($ability);
        // });
        
        //Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);
        // Event::listen(BuildingMenu::class, function (BuildingMenu $event) {
        //     // Add some items to the menu...
        //     //$event->menu->addBefore('pages',Auth::user()->user_name);
        //     $event->menu->addBefore('dashboard',[
        //         'text' => Auth::user()->name,
        //         'url' => 'profile',
        //         'icon' => 'fas fa-fw fa-user',
        //     ]);
        // });

    }
}

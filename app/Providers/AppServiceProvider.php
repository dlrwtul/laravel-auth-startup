<?php

namespace App\Providers;

use App\Enums\Roles;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

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
        Gate::define('create-user', function () {
            $user = User::findOrFail(Auth::user()->id);
            if (!$user->hasRole(Roles::SUPER_ADMIN->value))
                return false;
            return true;
        });
    }
}

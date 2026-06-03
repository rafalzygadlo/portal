<?php

namespace App\Providers;
use App\Models\User;
use App\Models\Business; // Assuming you have a Business model
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
        Gate::define('manage-business', function (User $user, string $subdomain) {
            return Business::where('subdomain', $subdomain)
                ->whereHas('users', function ($query) use ($user) {
                    $query->where('users.id', $user->id);
                })
                ->exists();
        });
    }
}
    

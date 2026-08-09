<?php

namespace App\Providers;
use App\Models\User;
use App\Models\Business; // Assuming you have a Business model
use Illuminate\Support\Facades\Gate;
use Illuminate\Database\Eloquent\Relations\Relation;

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
        //$this->registerMorphMap();
        $this->registerGates();
    }

    private function registerMorphMap()
    {
        Relation::enforceMorphMap([
            'article' => \App\Models\Article::class,
            'offer' => \App\Models\Offer::class,
            'todo' => \App\Models\Todo::class,
            'promotion' => \App\Models\Promotion::class,
            'business' => \App\Models\Business::class,
        ]);
    }

    private function registerGates()
    {
        // Użytkownik może dodawać zgłoszenia tylko jeśli ma dodatnią reputację
        Gate::define('create-report', function (User $user) {
            // Używamy progu z pliku konfiguracyjnego
            return $user->reputation_score > config('reputation.thresholds.can_create_content', 0);
        });
       
        // shows admin panel button if user is owner of business with subdomain
        Gate::define('manage-business', function (User $user, string $subdomain) {
            return Business::where('subdomain', $subdomain)
                ->whereHas('users', function ($query) use ($user) {
                    $query->where('users.id', $user->id);
                })
                ->exists();
        });
        
    }
}
    

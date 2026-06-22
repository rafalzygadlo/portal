<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\Business;
use App\Models\Article;
use App\Models\Offer;
use App\Models\Poll\Poll;
use App\Policies\BusinessPolicy;
use App\Policies\ArticlePolicy;
use App\Policies\OfferPolicy;
use App\Policies\PollPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Business::class => BusinessPolicy::class,
        Article::class => ArticlePolicy::class,
        Offer::class => OfferPolicy::class,
        Poll::class => PollPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}

<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\Announcement;
use App\Models\Article;
use App\Models\BookingFlow;
use App\Models\Business;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Favorite;
use App\Models\Image;
use App\Models\Notification;
use App\Models\Offer;
use App\Models\Report;
use App\Models\Reservation;
use App\Models\Resource;
use App\Models\Service;
use App\Models\Todo;
use App\Models\User;
use App\Models\Vote;
use App\Models\Poll\Poll;
use App\Models\Poll\PollOption;
use App\Policies\AnnouncementPolicy;
use App\Policies\BusinessPolicy;
use App\Policies\ArticlePolicy;
use App\Policies\BookingFlowPolicy;
use App\Policies\CategoryPolicy;
use App\Policies\CommentPolicy;
use App\Policies\FavoritePolicy;
use App\Policies\ImagePolicy;
use App\Policies\NotificationPolicy;
use App\Policies\OfferPolicy;
use App\Policies\PollPolicy;
use App\Policies\PollOptionPolicy;
use App\Policies\ReportPolicy;
use App\Policies\ReservationPolicy;
use App\Policies\ResourcePolicy;
use App\Policies\ServicePolicy;
use App\Policies\TodoPolicy;
use App\Policies\UserPolicy;
use App\Policies\VotePolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Announcement::class => AnnouncementPolicy::class,
        Article::class => ArticlePolicy::class,
        BookingFlow::class => BookingFlowPolicy::class,
        Business::class => BusinessPolicy::class,
        Category::class => CategoryPolicy::class,
        Comment::class => CommentPolicy::class,
        Favorite::class => FavoritePolicy::class,
        Image::class => ImagePolicy::class,
        Notification::class => NotificationPolicy::class,
        Offer::class => OfferPolicy::class,
        Poll::class => PollPolicy::class,
        PollOption::class => PollOptionPolicy::class,
        Report::class => ReportPolicy::class,
        Reservation::class => ReservationPolicy::class,
        Resource::class => ResourcePolicy::class,
        Service::class => ServicePolicy::class,
        Todo::class => TodoPolicy::class,
        User::class => UserPolicy::class,
        Vote::class => VotePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}

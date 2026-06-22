<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;



// Authenticated routes (User must be logged in)
    Route::middleware(['auth'])->group(function () {
        Route::post('/logout', [App\Livewire\Auth\Login::class, 'logout'])->name('logout');

        // Email Verification Routes
        Route::get('/email/verify', \App\Livewire\Auth\Verify::class)->name('verification.notice');

        Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
            $request->fulfill();
            return redirect()->intended(route('user.profile')); // Przekieruj po weryfikacji
        })->middleware(['signed'])->name('verification.verify');

        Route::post('/email/verification-notification', function (Request $request) {
            $request->user()->sendEmailVerificationNotification();
            return back()->with('message', 'Link weryfikacyjny został wysłany!');
        })->middleware(['throttle:6,1'])->name('verification.send');

        // Verified Routes (User must be logged in AND have verified email)
        Route::middleware(['verified'])->group(function () {
            Route::get('/profile', App\Livewire\Profile\Index::class)->name('user.profile');
            Route::get('/notify', App\Livewire\Notifications::class)->name('notifications.index');

            // Profile Management Routes
            Route::prefix('/profile')->group(function () {
                // Offers
                Route::get('/offer/create', App\Livewire\Profile\Offer\Create::class)->name('profile.offer.create');
                Route::get('/offer/{offer}', App\Livewire\Profile\Offer\Edit::class)->name('profile.offer.edit');

                // Articles
                Route::get('/article/create', App\Livewire\Profile\Article\Create::class)->name('profile.article.create');
                Route::get('/article/{article}', App\Livewire\Profile\Article\Edit::class)->name('profile.article.edit');

                // Business
                Route::get('/business/create', App\Livewire\Profile\Business\Create::class)->name('profile.business.create');
                Route::get('/business/{business}', App\Livewire\Profile\Business\Show::class)->name('profile.business.show');

                // Polls
                Route::get('/poll/create', App\Livewire\Profile\Poll\Create::class)->name('profile.poll.create');
                Route::get('/poll/{poll}', App\Livewire\Profile\Poll\Show::class)->name('profile.poll.show');
            });
        });
    });


    Route::get('/',App\Livewire\Main::class)->name('main.index');

    // Article routes
    Route::get('/articles', \App\Livewire\Article\Index::class)->name('articles.index');
    Route::get('/article/{article:slug}', \App\Livewire\Article\Show::class)->name('article.show');

    // Business routes
    Route::get('/businesses/{categorySlug?}', \App\Livewire\Business\Index::class)->name('business.index');
    Route::get('/business/{business:subdomain}', \App\Livewire\Business\Show::class)->name('business.show');

    Route::get('/page/{page}', \App\Livewire\Page::class)
        ->where('page', 'privacy|terms|faq')->name('page');

    // Todo routes
    Route::get('/todos', App\Livewire\Todo\Index::class)->name('todos.index');
    Route::get('/todo/{todo:slug}', App\Livewire\Todo\Show::class)->name('todo.show');

    // Offers
    Route::get('/offers/{categorySlug?}', \App\Livewire\Offer\Index::class)->name('offers.index');
    Route::get('/offer/{offer:slug}', \App\Livewire\Offer\Show::class)->name('offer.show');

    // Polls
    Route::get('/polls', \App\Livewire\Poll\Index::class)->name('polls.index');
    Route::get('/poll/{poll}', \App\Livewire\Poll\Show::class)->name('poll.show');

    // Guest routes (Only for users not logged in)
    Route::middleware('guest')->group(function () 
    {
        Route::get('/login', App\Livewire\Auth\Login::class)->name('login');
        Route::post('/login', [App\Livewire\Auth\Login::class, 'login']);
        Route::get('/register', App\Livewire\Auth\Register::class)->name('register');
        Route::post('/register', [App\Livewire\Auth\Register::class, 'register']);

        Route::get('/forgot-password', \App\Livewire\Auth\Password\Forgot::class)->name('password.request');
        Route::post('/forgot-password', [\App\Livewire\Auth\Password\Forgot::class, 'sendResetLink'])->name('password.email');
        Route::get('/reset-password/{token}', \App\Livewire\Auth\Password\Reset::class)->name('password.reset');
        Route::post('/reset-password', [\App\Livewire\Auth\Password\Reset::class, 'resetPassword'])->name('password.update');
    });




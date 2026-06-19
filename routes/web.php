<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

$domain = config('app.business_domain');
if (empty($domain)) 
{
    throw new \Exception('SYSTEM ERROR: The DOMAIN_NAME value in .env is empty. Configure it so subdomains work correctly.');
}

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
        Route::get('/profile', App\Livewire\Profile::class)->name('user.profile');
        Route::get('/notify', App\Livewire\Notifications::class)->name('notifications.index');
        Route::get('/offer/create', App\Livewire\Offer\Create::class)->name('offer.create');

    });
});


// Subdomains - business pages and bookings
Route::domain('{subdomain}.' . $domain)->group(function () {

    Route::get('/', \App\Livewire\Business\Domain::class)->name('business.domain');
    
    // TODO: Booking module - temporarily disabled
    // The booking system with multi-step flow (Step1-Step4) is under development
    // and will not be developed further in this iteration.
    // Routes remain commented out until the feature is ready for implementation.
    
    Route::prefix('booking')->group(function () {
        Route::get('/', \App\Livewire\Business\Booking\StartBooking::class)->name('business.booking');
        Route::get('/{flow}/step1', \App\Livewire\Business\Booking\Step1::class)->name('booking.step1');
        Route::get('/{flow}/step2', \App\Livewire\Business\Booking\Step2::class)->name('booking.step2');
        Route::get('/{flow}/step3', \App\Livewire\Business\Booking\Step3::class)->name('booking.step3');
        Route::get('/{flow}/step4', \App\Livewire\Business\Booking\Step4::class)->name('booking.step4');
    });
    
    
    Route::prefix('admin')->middleware(['auth', 'verified', 'can:manage-business,subdomain'])->group(function () {
        // Dashboard routes
        Route::get('/dashboard', \App\Livewire\Admin\Business\Dashboard::class)->name('admin.business.dashboard');
        
        // Services routes
        Route::get('/services', \App\Livewire\Admin\Business\Service\Index::class)->name('admin.business.services.index');
        //Route::get('/services/create', \App\Livewire\Admin\Business\Service\Create::class)->name('admin.business.services.create')->can('update,business');
        // Resource routes
        Route::get('/resources', \App\Livewire\Admin\Business\Resource\Index::class)->name('admin.business.resources.index');
        // ->can('update,business');
        //Route::get('/resources/create', \App\Livewire\Admin\Business\Resource\Create::class)->name('admin.business.resources.create')->can('update,business');
        // Reservations routes

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

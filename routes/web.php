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

// Subdomains - business pages and bookings
Route::domain('{subdomain}.' . $domain)->group(function () {

    Route::get('/login', \App\Livewire\Login::class)->name('business.login')->middleware('guest');
    Route::post('/login', [\App\Livewire\Login::class, 'login'])->name('business.login.submit');
    Route::get('/logout', [\App\Livewire\Login::class, 'logout'])->name('business.logout');
    Route::get('/login/verify/{email}', [\App\Livewire\Login::class, 'verify'])->name('business.login.verify');

    Route::get('/', \App\Livewire\Business\Domain::class)->name('business.domain');
    
    Route::prefix('booking')->group(function () {
        Route::get('/', \App\Livewire\Business\Booking\StartBooking::class)->name('business.booking');
        Route::get('/{flow}/step1', \App\Livewire\Business\Booking\Step1::class)->name('booking.step1');
        Route::get('/{flow}/step2', \App\Livewire\Business\Booking\Step2::class)->name('booking.step2');
        Route::get('/{flow}/step3', \App\Livewire\Business\Booking\Step3::class)->name('booking.step3');
        Route::get('/{flow}/step4', \App\Livewire\Business\Booking\Step4::class)->name('booking.step4');
    });
    
    Route::prefix('admin')->middleware(['auth'])->group(function () {
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
Route::get('/articles/create', \App\Livewire\Article\Create::class)->name('articles.create')->middleware('auth');
Route::get('/articles/{article}', \App\Livewire\Article\Show::class)->name('articles.show');

// Business routes
Route::get('/business', \App\Livewire\Business\Index::class)->name('business.index');
Route::get('/business/create', \App\Livewire\Business\Create::class)->name('business.create')->middleware('auth');
Route::get('/business/{business:subdomain}', \App\Livewire\Business\Show::class)->name('business.show');


Route::get('/page/{page}', \App\Livewire\Page::class)
    ->where('page', 'privacy|terms|faq')->name('page');

Route::get('/profile/{user}', App\Livewire\Profile::class)->name('user.profile');
// Todo routes
Route::get('/todos', App\Livewire\Todo\Index::class)->name('todos.index');
Route::get('/todos/create', App\Livewire\Todo\Create::class)->name('todos.create')->middleware('auth');
Route::get('/todos/{todo}', App\Livewire\Todo\Show::class)->name('todos.show');

// Offers
Route::get('/offers', \App\Livewire\Offer\Index::class)->name('offers.index');
Route::get('/offers/create', \App\Livewire\Offer\Create::class)->name('offers.create')->middleware('auth');
Route::get('/offers/{offer}', \App\Livewire\Offer\Show::class)->name('offers.show');

// Polls
Route::get('/polls', \App\Livewire\Poll\Index::class)->name('polls.index');
Route::get('/polls/create', \App\Livewire\Poll\Create::class)->name('polls.create')->middleware('auth');
Route::get('/polls/{poll}', \App\Livewire\Poll\Show::class)->name('polls.show');

Route::get('/notify',App\Livewire\Notifications::class)->name('notifications.index')->middleware('auth');

Route::get('/login',App\Livewire\Login::class)->name('login')->middleware('guest');
Route::post('/login',[App\Livewire\Login::class,'login']);
Route::get('/logout',[App\Livewire\Login::class,'logout'])->name('logout');
Route::get('/login/verify/{email}', [App\Livewire\Login::class, 'verify'])->name('login.verify');





// 


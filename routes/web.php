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

// Subdomeny - Strony biznesu i rezerwacje
Route::domain('{subdomain}.localhost')->group(function () {
    Route::get('/', \App\Livewire\Business\Domain::class)->name('business.domain');
    Route::get('/booking', \App\Livewire\Business\Booking::class)->name('business.booking');
    
    Route::middleware(['auth'])->group(function () {
        Route::get('/dashboard/business/{business}/reservations', \App\Livewire\Business\Dashboard::class)->name('dashboard.business')->can('update,business');
    });

});


// Article routes
Route::get('/',App\Livewire\Main::class)->name('main.index');
Route::get('/articles/create', \App\Livewire\Article\Form::class)->name('article.create')->middleware('auth');
Route::get('/article/{article}', \App\Livewire\Article\Show::class)->name('article.show');

// Business routes
Route::get('/business', \App\Livewire\Business\Index::class)->name('business.index');
Route::get('/business/create', \App\Livewire\Business\Create::class)->name('business.create')->middleware('auth');
Route::get('/business/{business:slug}', \App\Livewire\Business\Show::class)->name('business.show');


Route::get('/page/{page}', \App\Livewire\Page::class)
    ->where('page', 'privacy|terms|faq')->name('page');

Route::get('/profile/{user}', App\Livewire\Profile::class)->name('user.profile');
// Todo routes
Route::get('/todo', App\Livewire\Todo\Index::class)->name('todo.index');
Route::get('/todo/create', App\Livewire\Todo\Create::class)->name('todo.create')->middleware('auth');
Route::get('/todo/{todo}', App\Livewire\Todo\Show::class)->name('todo.show');

// Offers
Route::get('/offers', \App\Livewire\Offer\Index::class)->name('offers.index');
Route::get('/offers/create', \App\Livewire\Offer\Create::class)->name('offers.create')->middleware('auth');
Route::get('/offers/{offer}', \App\Livewire\Offer\Show::class)->name('offers.show');

// Announcements (deprecated - replaced by Offers)
Route::get('/announcements', \App\Livewire\Announcement\Index::class)->name('announcements.index');
Route::get('/announcements/create', \App\Livewire\Announcement\Create::class)->name('announcements.create')->middleware('auth');
Route::get('/announcements/{announcement}', \App\Livewire\Announcement\Show::class)->name('announcements.show');

// Polls
Route::get('/polls', \App\Livewire\Poll\Index::class)->name('polls.index');
Route::get('/polls/create', \App\Livewire\Poll\Create::class)->name('polls.create')->middleware('auth');
Route::get('/polls/{poll}', \App\Livewire\Poll\Show::class)->name('polls.show');

Route::get('/notify',App\Livewire\Notifications::class)->name('notifications.index')->middleware('auth');

Route::get('/login',App\Livewire\Login::class)->name('login')->middleware('guest');
Route::post('/login',[App\Livewire\Login::class,'login']);
Route::get('/logout',[App\Livewire\Login::class,'logout'])->name('logout');




Route::get('/login/verify/{email}', function (Request $request, $email) {
    if (! $request->hasValidSignature()) 
    {
        abort(403, 'Link logowania wygasł lub jest nieprawidłowy.');
    }

    $user = User::firstOrCreate(
        ['email' => $email],
        [
            'first_name' => Str::before($email, '@'), 
            'last_name' => '',
            'password' => Hash::make(Str::random(24)), 
        ]
    );

    Auth::login($user);

    return redirect('/');
})->name('login.verify');

// 


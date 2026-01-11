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

Route::get('/',App\Livewire\Main::class);
Route::get('/article/{article}', \App\Livewire\Article\Show::class)->name('article.show');

Route::get('/page/{page}', \App\Livewire\Page::class)
    ->where('page', 'privacy|terms|faq')->name('page');

Route::get('/profile/{user}', App\Livewire\Profile::class)->name('user.profile');


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

Route::middleware('auth')->group(function () {
    Route::get('/articles/create', \App\Livewire\Article\Form::class)->name('article.create');
    //Route::get('/admin/reports', \App\Livewire\Admin\ReportedArticles::class)->name('admin.reports');
});

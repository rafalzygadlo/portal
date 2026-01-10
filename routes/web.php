<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

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

Route::get('/',App\Livewire\Main::class);// user routes


Route::get('/login',App\Livewire\Login::class);
Route::post('/login',[App\Livewire\Login::class,'login']);
Route::get('/logout',[App\Livewire\Login::class,'logout']);


Route::group([
    'middleware' => ['auth','verified'],
    /* 'as' => 'usr.'*/
], function ()
{

 
});


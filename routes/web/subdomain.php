<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

// Subdomains - company pages and bookings


    Route::get('/', \App\Livewire\Company\Domain::class)->name('company.domain');
    Route::get('/book-equipment', \App\Livewire\Company\BookResource::class)->name('company.booking.equipment');
    Route::get('/book-service', \App\Livewire\Company\BookService::class)->name('company.booking.services');
    
    Route::middleware('guest')->group(function () 
    {
        Route::get('/login', App\Livewire\Auth\Login::class)->name('login.subdomain');
    });

    Route::middleware('auth')->group(function () {
        Route::post('/logout', [App\Livewire\Auth\Login::class, 'logout'])->name('logout.subdomain');
    });
    
    Route::prefix('admin')->middleware(['auth', 'verified', 'can:manage,company'])->group(function () 
    {
        // Dashboard routes
        Route::get('/dashboard', \App\Livewire\Admin\Company\Dashboard::class)->name('admin.company.dashboard');
        Route::get('/my-tasks', \App\Livewire\Admin\Company\MyTasks\Index::class)->name('admin.company.my-tasks');
        Route::get('/users', \App\Livewire\Admin\Company\User\Index::class)->name('admin.company.users');
        Route::get('/subscription', \App\Livewire\Admin\Company\Subscription::class)->name('admin.company.subscription');
        Route::get('/working-hours', \App\Livewire\Admin\Company\WorkingHours::class)->name('admin.company.settings.working-hours');
        
        // Services routes
        Route::get('/services', \App\Livewire\Admin\Company\Service\Index::class)->name('admin.company.services');
        //Route::get('/services/create', \App\Livewire\Admin\Company\Service\Create::class)->name('admin.company.services.create')->can('update,company');
        // Resource routes
        Route::get('/resources', \App\Livewire\Admin\Company\Resource\Index::class)->name('admin.company.resources');
        Route::get('/resource-bookings', \App\Livewire\Admin\Company\ResourceBooking\Index::class)->name('admin.company.reservations.resources');
        Route::get('/reservations', \App\Livewire\Admin\Company\ServiceBooking\Index::class)->name('admin.company.reservations.services');
        // ->can('update,company');
        //Route::get('/resources/create', \App\Livewire\Admin\Company\Resource\Create::class)->name('admin.company.resources.create')->can('update,company');
        // Reservations routes
        Route::get('/modules', \App\Livewire\Admin\Company\Modules::class)
            ->name('admin.company.modules');

    });

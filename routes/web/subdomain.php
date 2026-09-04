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
    Route::get('/equipment', \App\Livewire\Company\BookEquipment::class)->name('company.booking.equipment');
    
    // TODO: Booking module - temporarily disabled
    // The booking system with multi-step flow (Step1-Step4) is under development
    // and will not be developed further in this iteration.
    // Routes remain commented out until the feature is ready for implementation.
    
    Route::prefix('booking')->group(function () 
    {
        Route::get('/', \App\Livewire\Company\BookService::class)->name('company.booking.services');
        Route::get('/{flow}/step1', \App\Livewire\Company\Booking\Step1::class)->name('booking.step1');
        Route::get('/{flow}/step2', \App\Livewire\Company\Booking\Step2::class)->name('booking.step2');
        Route::get('/{flow}/step3', \App\Livewire\Company\Booking\Step3::class)->name('booking.step3');
        Route::get('/{flow}/step4', \App\Livewire\Company\Booking\Step4::class)->name('booking.step4');
    });
    
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

    });

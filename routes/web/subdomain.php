<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

// Subdomains - business pages and bookings


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



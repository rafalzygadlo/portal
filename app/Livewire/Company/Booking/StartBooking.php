<?php

/**
 * DEPRECATED - Booking module temporarily disabled
 * This component is part of the booking system that is not being developed in the current iteration.
 * DO NOT USE - Routes for this component are commented out in routes/web.php
 */

namespace App\Livewire\Company\Booking;

use Livewire\Component;
use App\Models\Company;
use App\Models\BookingFlow;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Collection;

class StartBooking extends Component
{
    
    public function mount(Company $company)
    {
        //usiness = Company::where('subdomain', $subdomain)->firstOrFail();
        
        $flow = BookingFlow::create([
            'company_id' => $company->id,
            'status' => 'draft',
            'expires_at' => now()->addMinutes(30),
        ]);
        
        return redirect()->route('booking.step1', [
            'company' => $company,
            'flow' => $flow->id,
        ]);
    }

    public function render()
    {
        return view('livewire.company.booking.start-booking');
    }
}

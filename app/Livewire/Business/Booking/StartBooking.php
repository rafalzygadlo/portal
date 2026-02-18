<?php

namespace App\Livewire\Business\Booking;

use Livewire\Component;
use App\Models\Business;
use App\Models\BookingFlow;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Collection;

class StartBooking extends Component
{
    
    public function mount(string $subdomain)
    {
        $business = Business::where('subdomain', $subdomain)->firstOrFail();

        $flow = BookingFlow::create([
            'business_id' => $business->id,
            'status' => 'draft',
            'expires_at' => now()->addMinutes(30),
        ]);

        return redirect()->route('booking.step1', [
            'flow' => $flow->id,
        ]);
    }

    public function render()
    {
        return view('livewire.business.booking.start-booking');
    }
}

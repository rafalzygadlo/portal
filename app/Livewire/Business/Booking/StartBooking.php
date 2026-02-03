<?php

namespace App\Livewire\Business\Booking;

use Livewire\Component;
use App\Models\Business;
use Illuminate\Database\Eloquent\Collection;

class StartBooking extends Component
{
    
    public function mount(string $subdomain)
    {
        $business = Business::where('subdomain', $subdomain)->firstOrFail();

        $flow = BookingFlow::create([
            'id' => Str::uuid(),
            'business_id' => $business->id,
            'status' => 'draft',
            'expires_at' => now()->addMinutes(30),
        ]);

        return redirect()->route('booking.step1', [
            'flowId' => $flow->id,
        ]);
    }

    public function render()
    {
        return null;
    }
}

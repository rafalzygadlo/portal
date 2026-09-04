<?php

namespace App\Livewire\Admin\Business\ResourceBooking;

use App\Models\Business;
use App\Models\ResourceBooking;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class Index extends Component
{
    use AuthorizesRequests;

    public Business $business;
    public function mount(Business $business): void
    {
        $this->authorize('update', $business);
        $this->business = $business;
    }
    public array $expandedDates = [];

    public function toggleDate(string $date): void
    {
        if (in_array($date, $this->expandedDates, true)) {
            $this->expandedDates = array_values(array_diff($this->expandedDates, [$date]));
            return;
        }

        $this->expandedDates[] = $date;
    }

    public function cancelBooking(int $bookingId): void
    {
        $this->authorize('update', $this->business);
        $booking = $this->business->resourceBookings()->findOrFail($bookingId);
        $booking->update(['status' => 'cancelled']);
        session()->flash('success', 'Resource booking has been cancelled.');
    }

    public function confirmBooking(int $bookingId): void
    {
        $this->authorize('update', $this->business);
        $booking = $this->business->resourceBookings()->findOrFail($bookingId);
        $booking->update(['status' => 'confirmed']);
        session()->flash('success', 'Resource booking has been confirmed.');
    }

    public function render()
    {
        return view('livewire.admin.business.resource-booking.index', [
            'bookings' => $this->business->resourceBookings()->with('resource')->latest('start_time')->get(),
        ])->layout('layouts.admin', ['business' => $this->business]);
    }
}
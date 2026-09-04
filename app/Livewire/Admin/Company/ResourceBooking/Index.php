<?php

namespace App\Livewire\Admin\Company\ResourceBooking;

use App\Models\Company;
use App\Models\ResourceBooking;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class Index extends Component
{
    use AuthorizesRequests;

    public Company $company;
    public function mount(Company $company): void
    {
        $this->authorize('update', $company);
        $this->company = $company;
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
        $this->authorize('update', $this->company);
        $booking = $this->company->resourceBookings()->findOrFail($bookingId);
        $booking->update(['status' => 'cancelled']);
        session()->flash('success', 'Resource booking has been cancelled.');
    }

    public function confirmBooking(int $bookingId): void
    {
        $this->authorize('update', $this->company);
        $booking = $this->company->resourceBookings()->findOrFail($bookingId);
        $booking->update(['status' => 'confirmed']);
        session()->flash('success', 'Resource booking has been confirmed.');
    }

    public function render()
    {
        return view('livewire.admin.company.resource-booking.index', [
            'bookings' => $this->company->resourceBookings()->with('resource')->latest('start_time')->get(),
        ])->layout('layouts.admin', ['company' => $this->company]);
    }
}
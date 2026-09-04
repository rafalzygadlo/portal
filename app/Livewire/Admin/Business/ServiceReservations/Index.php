<?php

namespace App\Livewire\Admin\Business\ServiceReservations;

use App\Models\Business;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class Index extends Component
{
    use AuthorizesRequests;

    public Business $business;
    public string $resourceFilter = '';
    public string $statusFilter = '';

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

    public function confirmReservation(int $reservationId): void
    {
        $reservation = $this->business->reservations()->findOrFail($reservationId);
        $reservation->update(['status' => 'confirmed']);
    }

    public function cancelReservation(int $reservationId): void
    {
        $reservation = $this->business->reservations()->findOrFail($reservationId);
        $reservation->update(['status' => 'cancelled']);
    }

    public function render()
    {
        $query = $this->business->reservations()
            ->with(['service', 'services', 'resource'])
            ->orderBy('start_time');

        if ($this->resourceFilter) {
            $query->where('resource_id', $this->resourceFilter);
        }

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        return view('livewire.admin.business.service-reservations.index', [
            'reservations' => $query->get(),
            'people' => $this->business->resources()->where('type', 'person')->orderBy('name')->get(),
        ])->layout('layouts.admin', ['business' => $this->business]);
    }
}
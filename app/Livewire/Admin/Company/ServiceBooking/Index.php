<?php

namespace App\Livewire\Admin\Company\ServiceBooking;

use App\Models\Company;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class Index extends Component
{
    use AuthorizesRequests;

    public Company $company;
    public string $resourceFilter = '';
    public string $statusFilter = '';

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

    public function confirmReservation(int $reservationId): void
    {
        $reservation = $this->company->reservations()->findOrFail($reservationId);
        $reservation->update(['status' => 'confirmed']);
    }

    public function cancelReservation(int $reservationId): void
    {
        $reservation = $this->company->reservations()->findOrFail($reservationId);
        $reservation->update(['status' => 'cancelled']);
    }

    public function render()
    {
        $query = $this->company->reservations()
            ->with(['service', 'services', 'resource'])
            ->orderBy('start_time');

        if ($this->resourceFilter) {
            $query->where('resource_id', $this->resourceFilter);
        }

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        return view('livewire.admin.company.service-booking.index', [
            'reservations' => $query->get(),
            'people' => $this->company->resources()->where('type', 'person')->orderBy('name')->get(),
        ])->layout('layouts.admin', ['company' => $this->company]);
    }
}
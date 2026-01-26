<?php

namespace App\Livewire;

use App\Models\Business;
use App\Models\Reservation;
use Livewire\Component;
use Livewire\WithPagination;

class EmployeeCalendar extends Component
{
    use WithPagination;

    public Business $business;
    public string $selectedDate = '';
    public array $reservations = [];

    public function mount(Business $business)
    {
        // Sprawdzenie czy user pracuje w biznesie
        $this->business = $business;
        $this->selectedDate = now()->format('Y-m-d');
    }

    public function updatedSelectedDate($value)
    {
        $this->loadReservations();
    }

    protected function loadReservations(): void
    {
        $date = $this->selectedDate;
        
        $this->reservations = $this->business->reservations()
            ->whereDate('start_time', $date)
            ->where('status', '!=', 'cancelled')
            ->with('service', 'user')
            ->orderBy('start_time')
            ->get()
            ->toArray();
    }

    public function render()
    {
        $this->loadReservations();
        
        return view('livewire.employee-calendar', [
            'reservations' => $this->reservations,
        ]);
    }
}

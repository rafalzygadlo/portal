<?php

namespace App\Livewire\Business\Booking;

use App\Models\ReservationService;
use Livewire\Component;
use Carbon\Carbon;

class Step4 extends Component
{
    public ?ReservationService $selectedService;
    public string $selectedDate;
    public string $selectedTime;
    public string $clientName;
    public string $clientEmail;

    public function mount(?ReservationService $selectedService, string $selectedDate, string $selectedTime, string $clientName, string $clientEmail)
    {
        $this->selectedService = $selectedService;
        $this->selectedDate = $selectedDate;
        $this->selectedTime = $selectedTime;
        $this->clientName = $clientName;
        $this->clientEmail = $clientEmail;
    }

    public function render()
    {
        return view('livewire.business.booking.step4');
    }
}

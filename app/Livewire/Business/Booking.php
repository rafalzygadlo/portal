<?php

namespace App\Livewire\Business;

use App\Models\Business;
use App\Models\Reservation;
use App\Models\ReservationService;
use Livewire\Component;
use Livewire\Attributes\Computed;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\On;

class Booking extends Component
{
    public Business $business;
    public string $selectedServiceId = '';
    public string $selectedDate = '';
    public string $selectedTime = '';
    public string $clientName = '';
    public string $clientEmail = '';
    public string $clientPhone = '';
    public string $notes = '';
    public Collection $services;
    public int $step = 1;

    #[Computed]
    public function selectedService()
    {
        return $this->selectedServiceId ? ReservationService::find($this->selectedServiceId) : null;
    }

    public function mount($subdomain)
    {
        $this->business = Business::where('subdomain', $subdomain)->firstOrFail();
        $this->services = $this->business->services()->where('is_active', true)->get();
    }

    #[On('serviceSelected')]
    public function serviceSelected($serviceId)
    {
        $this->selectedServiceId = $serviceId;
        $this->selectedTime = '';
        if ($serviceId) {
            $this->nextStep();
        }
    }

    #[On('dateTimeSelected')]
    public function dateTimeSelected($date, $time)
    {
        $this->selectedDate = $date;
        $this->selectedTime = $time;
    }

    public function nextStep()
    {
        
        $this->validateStep($this->step);

        if ($this->step < 4) {
            $this->step++;
        }
    }

    public function previousStep()
    {
        if ($this->step > 1) {
            $this->step--;
        }
    }

    public function goToStep($step)
    {
        if ($step > $this->step) {
            for ($i = $this->step; $i < $step; $i++) {
                $this->validateStep($i);
            }
        }
        $this->step = $step;
    }

    protected function validateStep($step)
    {
        if ($step == 1) 
        {
            $this->validate(['selectedServiceId' => 'required']);
        } 

        if ($step == 2) 
        {
            $this->validate([
                'selectedDate' => 'required|date',
                'selectedTime' => 'required'
            ]);
        } 
        
        if ($step == 3) 
        {
            
            $this->validate([
                'clientName' => 'required|string|min:3',
                'clientEmail' => 'required|email',
            ]);
        }
    }
    
    public function book()
    {
        $this->validate([
            'selectedServiceId' => 'required',
            'selectedDate' => 'required|date',
            'selectedTime' => 'required',
            'clientName' => 'required|string',
            'clientEmail' => 'required|email',
        ]);

        $startTime = Carbon::parse($this->selectedDate . ' ' . $this->selectedTime);
        $service = $this->selectedService();
        
        if (!Reservation::isTimeSlotAvailable(
            $this->business->id,
            $service->id,
            $startTime->format('Y-m-d H:i:s'),
            $startTime->copy()->addMinutes($service->duration_minutes)->format('Y-m-d H:i:s')
        )) {
            $this->addError('selectedTime', 'Wybrany termin jest już zajęty.');
            $this->dispatch('notify', message: 'Wybrany termin jest już zajęty.', type: 'error');
            return;
        }

        Reservation::create([
            'business_id' => $this->business->id,
            'reservation_service_id' => $service->id,
            'user_id' => auth()->id(),
            'client_name' => $this->clientName,
            'client_email' => $this->clientEmail,
            'client_phone' => $this->clientPhone,
            'start_time' => $startTime,
            'end_time' => $startTime->copy()->addMinutes($service->duration_minutes),
            'notes' => $this->notes,
            'status' => 'pending',
        ]);

        session()->flash('success', 'Rezerwacja została złożona! Czekamy na potwierdzenie.');
        $this->resetExcept('business', 'services');
    }

    public function render()
    {
        return view('livewire.business.booking')->layout('layouts.business', ['business' => $this->business]);
    }
}
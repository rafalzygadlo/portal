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
use Illuminate\Database\Eloquent\Collection;

class Step1 extends Component
{
    public BookingFlow $flow;
    public Collection $services;
    public string $selectedServiceId = '';
    public string $selectedDate = '';
    public Company $company;

    public function mount(BookingFlow $flow)
    {
        $this->flow = $flow;
        $this->company = $this->flow->company;
        $this->services = $this->company->services()->where('is_active', true)->get();
        
        // Pre-fill from flow data if available
        $this->selectedServiceId = $this->flow->data['service_id'] ?? '';

    }

    public function nextStep()
    {
        $this->validate([
            'selectedServiceId' => 'required|exists:services,id',
        ]);
        
        $this->flow->update([
            'data' => array_merge($this->flow->data ?? [], [
                'service_id' => $this->selectedServiceId,
                'date' => $this->selectedDate,
            ]),
        ]);

        return redirect()->route('booking.step2', 
        ['flow' => $this->flow->id, 'company' => $this->company
        ]);
    }

    public function selectService($serviceId)
    {
        $this->selectedServiceId = $serviceId;
    }

    public function render()
    {
        return view('livewire.company.booking.step1', ['company' => $this->company])
                ->layout('layouts.company', ['company' => $this->company]);
    }
}

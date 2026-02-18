<?php

namespace App\Livewire\Business\Booking;

use Livewire\Component;
use App\Models\Business;
use App\Models\BookingFlow;
use Illuminate\Database\Eloquent\Collection;

class Step1 extends Component
{
    public BookingFlow $flow;
    public Collection $services;
    public string $selectedServiceId = '';
    public string $selectedDate = '';
    public Business $business;

    public function mount(BookingFlow $flow)
    {
        $this->flow = $flow;
        $this->business = $this->flow->business;
        $this->services = $this->business->services()->where('is_active', true)->get();
        
        // Pre-fill from flow data if available
        $this->selectedServiceId = $this->flow->data['service_id'] ?? '';
        $this->selectedDate = $this->flow->data['date'] ?? '';
    }

    public function nextStep()
    {
        $this->validate([
            'selectedServiceId' => 'required|exists:reservation_services,id',
            'selectedDate' => 'required|date|after_or_equal:today',
        ]);
        
        $this->flow->update([
            'data' => array_merge($this->flow->data ?? [], [
                'service_id' => $this->selectedServiceId,
                'date' => $this->selectedDate,
            ]),
        ]);

        return redirect()->route('booking.step2', ['flow' => $this->flow->id]);
    }

    public function selectService($serviceId)
    {
        $this->selectedServiceId = $serviceId;
    }

    public function render()
    {
        return view('livewire.business.booking.step1', ['business' => $this->business])
                ->layout('layouts.business', ['business' => $this->business]);
    }
}

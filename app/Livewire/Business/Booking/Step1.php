<?php

namespace App\Livewire\Business\Booking;

use Livewire\Component;
use Illuminate\Database\Eloquent\Collection;

class Step1 extends Component
{
    public Collection $services;
    public string $selectedServiceId = '';

    public function mount(Collection $services, string $selectedServiceId)
    {
        $this->services = $services;
        $this->selectedServiceId = $selectedServiceId;
    }

    public function selectService($serviceId)
    {
        $this->selectedServiceId = $serviceId;
        $this->dispatch('serviceSelected', $serviceId);
    }

    public function render()
    {
        return view('livewire.business.booking.step1');
    }
}

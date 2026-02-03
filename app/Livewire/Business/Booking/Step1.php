<?php

namespace App\Livewire\Business\Booking;

use Livewire\Component;
use App\Models\Business;
use Illuminate\Database\Eloquent\Collection;

class Step1 extends Component
{
    public Collection $services;
    public string $selectedServiceId = '';
    public Business $business;


    public function mount($subdomain)
    {
        $this->business = Business::where('subdomain', $subdomain)->firstOrFail();
        $this->services = $this->business->services()->where('is_active', true)->get();
    }

    public function nextStep()
    {      
        
        //$this->dispatch('serviceSelected', $serviceId);
    }

    public function selectService($serviceId)
    {
        $this->selectedServiceId = $serviceId;
        //$this->dispatch('serviceSelected', $serviceId);
    }

    public function render()
    {
        return view('livewire.business.booking.step1',['business' => $this->business])
                ->layout('layouts.business', ['business' => $this->business]);
    }
}

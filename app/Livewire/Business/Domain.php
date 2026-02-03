<?php

namespace App\Livewire\Business;

use App\Models\Business;
use Livewire\Component;

class Domain extends Component
{
    public Business $business;

    public function mount($subdomain)
    {
        $this->business = Business::where('subdomain', $subdomain)->firstOrFail();
        
    }

    public function render()
    {
        return view('livewire.business.domain', [
            'services' => $this->business->services()->where('is_active', true)->get(),
            'isOwner' => auth()->check() && auth()->user()->id === $this->business->user_id,
            'business' => $this->business->name,
        ])->layout('layouts.business', [
            'business' => $this->business,
        ]);
    }
}

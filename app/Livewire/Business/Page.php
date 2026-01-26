<?php

namespace App\Livewire\Business;

use App\Models\Business;
use Livewire\Component;

class Page extends Component
{
    public Business $business;

    public function mount(Business $business)
    {
        $this->business = $business;
    }

    public function render()
    {
        return view('livewire.business.page', [
            'services' => $this->business->services()->where('is_active', true)->get(),
            'isOwner' => auth()->check() && auth()->user()->id === $this->business->user_id,
        ]);
    }
}

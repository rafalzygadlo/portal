<?php

namespace App\Livewire\Business;

use App\Models\Business;
use Livewire\Component;

class Show extends Component
{
    public Business $business;

    public function mount(Business $business)
    {
        $this->business = $business;
    }

    public function render()
    {
        return view('livewire.business.show');
    }
}

<?php

namespace App\Livewire\Business\Resource;

use App\Models\Business;
use Livewire\Component;

class Index extends Component
{
    public Business $business;

    public function mount(Business $business)
    {
        $this->business = $business;
    }

    public function render()
    {
        return view('livewire.business.resource.index', [
            'resources' => $this->business->resources()->get(),
        ])->layout('layouts.business', ['business' => $this->business]);
    }
}

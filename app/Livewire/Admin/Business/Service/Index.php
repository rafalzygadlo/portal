<?php

namespace App\Livewire\Admin\Business\Service;

use App\Models\Business;
use Livewire\Component;

class Index extends Component
{
    public Business $business;

    public function mount($subdomain)
    {
        $this->business = Business::where('subdomain', $subdomain)->firstOrFail();
    }

    public function render()
    {

        return view('livewire.admin.business.service.index', [
            'services' => $this->business->services()->get(),
        ])->layout('layouts.business', ['business' => $this->business]);
    }
}

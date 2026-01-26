<?php

namespace App\Livewire\Business;

use App\Models\Business;
use Livewire\Component;

class PublicPage extends Component
{
    public Business $business;

    public function mount($subdomain)
    {
        $this->business = Business::where('subdomain', $subdomain)->firstOrFail();
    }

    public function render()
    {
        return view('livewire.business.public-page', [
            'services' => $this->business->services()->where('is_active', true)->get(),
        ]);
    }
}

<?php

namespace App\Livewire\Profile\Business;

use Livewire\Component;
use App\Models\Business;

class Index extends Component
{
    public function render()
    {
        $businesses = auth()->user()->ownedBusinesses()->latest()->get();
        
        return view('livewire.profile.business.index', [
            'businesses' => $businesses
        ]);
    }
}

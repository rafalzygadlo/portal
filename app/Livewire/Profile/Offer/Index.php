<?php

namespace App\Livewire\Profile\Offer;

use Livewire\Component;
use App\Models\Offer;

class Index extends Component
{
    public function render()
    {
        $offers = auth()->user()->offers()->latest()->get();
        
        return view('livewire.profile.offer.index', [
            'offers' => $offers
        ]);
    }
}

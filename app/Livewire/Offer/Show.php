<?php

namespace App\Livewire\Offer;

use Livewire\Component;
use App\Models\Offer\Offer;

class Show extends Component
{
    public Offer $offer;

    public function mount(Offer $offer)
    {
        $this->offer = $offer;
    }

    public function render()
    {
        return view('livewire.offer.show');
    }
}

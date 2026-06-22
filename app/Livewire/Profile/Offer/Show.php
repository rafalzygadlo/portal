<?php

namespace App\Livewire\Profile\Offer;

use App\Models\Offer;

class Show extends Edit
{
    public function mount(Offer $offer)
    {
        parent::mount($offer);
    }
}

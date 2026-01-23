<?php

namespace App\Livewire\Offer;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Offer\Offer;

class Index extends Component
{
    use WithPagination;

    public function render()
    {
        $offers = Offer::with('user', 'category')->latest()->paginate(10);

        return view('livewire.offer.index', [
            'offers' => $offers
        ]);
    }
}

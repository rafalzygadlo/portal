<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Offer\Offer;

class Main extends Component
{
    public function render()
    {
        $feed = Offer::with('categories')->latest()->take(20)->get();

        return view('livewire.main', compact('feed'));
    }
}

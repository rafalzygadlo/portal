<?php

namespace App\Livewire\Offer;

use Livewire\Component;
use App\Models\Offer;
use Livewire\Attributes\Computed;

class Show extends Component
{
    public Offer $offer;

    public function mount(Offer $offer)
    {
        // Jeśli model jest wstrzyknięty przez Route Model Binding, 
        // doładowujemy tylko brakujące relacje
        $this->offer = $offer;
        $this->offer->loadMissing(['categories.parent', 'user', 'images']);
    }

    #[Computed]
    public function breadcrumb()
    {
        if ($this->offer->categories->isNotEmpty()) {
            return $this->offer->categories->first()->getBreadcrumbs();
        }
        
        return collect(); // Zwróć pustą kolekcję, jeśli nie ma kategorii
    }

    public function render()
    {
        return view('livewire.offer.show');
    }
}

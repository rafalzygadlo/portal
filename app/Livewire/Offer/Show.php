<?php

namespace App\Livewire\Offer;

use Livewire\Component;
use App\Models\Offer\Offer;
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
        $path = collect();
        
        if ($this->offer->categories->isNotEmpty()) {
            $current = $this->offer->categories->first();
            while ($current) {
                $path->prepend($current);
                $current = $current->parent;
            }
        }
        
        return $path;
    }

    public function render()
    {
        return view('livewire.offer.show');
    }
}

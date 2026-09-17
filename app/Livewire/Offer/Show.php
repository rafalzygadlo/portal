<?php

namespace App\Livewire\Offer;

use Livewire\Component;
use App\Models\Offer;
use Livewire\Attributes\Computed;

class Show extends Component
{
    public Offer $offer;

    public $currentCategory;

    public $categorySlug;
    public function mount(Offer $offer)
    {
        $this->offer = $offer;
        $this->offer->loadMissing(['categories.parent', 'user', 'images']);
        $this->currentCategory = $this->offer->categories->first();
        $this->categorySlug = $this->currentCategory?->slug;
    }


    public function render()
    {
        return view('livewire.offer.show');
    }
}

<?php

namespace App\Livewire\Offer;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Offer\Offer;

class Index extends Component
{
    use WithPagination;

    #[Session]
    public $selectedCategories = [];

    public function render()
    {
        $offers = Offer::with('user')->
        //whereHas('categories', function ($q) {
          //  $q->whereIn('slug', $this->selectedCategories);
        //})->
         latest()->paginate(10);

        return view('livewire.offer.index', [
            'offers' => $offers
        ]);
    }
}

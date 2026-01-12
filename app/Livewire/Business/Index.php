<?php

namespace App\Livewire\Business;

use App\Models\Business;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public function render()
    {
        $businesses = Business::where('is_approved', true)
            ->latest()
            ->paginate(10);

        return view('livewire.business.index', [
            'businesses' => $businesses,
        ]);
    }
}

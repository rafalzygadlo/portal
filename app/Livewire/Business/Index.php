<?php

namespace App\Livewire\Business;

use App\Models\Business;
use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Session;

class Index extends Component
{
    use WithPagination;

    #[Session]
    public $selectedCategories = [];

    public function updatedSelectedCategories()
    {
        $this->resetPage();
    }

    public function render()
    {
        $businesses = Business::
            when($this->selectedCategories, function ($query) {
                $query->whereHas('categories', function ($q) {
                    $q->whereIn('slug', $this->selectedCategories);
                });
            })
            ->latest()
            ->paginate(6);

        return view('livewire.business.index', [
            'businesses' => $businesses,
            'categories' => Category::orderBy('name')->get(),
        ]);
    }
}

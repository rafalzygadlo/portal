<?php

namespace App\Livewire\Business;

use App\Models\Business;
use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $category = null;

    public function filterByCategory($categorySlug)
    {
        $this->category = $categorySlug;
        $this->resetPage();
    }

    public function render()
    {
        $businesses = Business::where('is_approved', true)
            ->when($this->category, function ($query) {
                $query->whereHas('categories', function ($q) {
                    $q->where('slug', $this->category);
                });
            })
            ->latest()
            ->paginate(10);

        return view('livewire.business.index', [
            'businesses' => $businesses,
            'categories' => Category::all(),
        ]);
    }
}

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

    public $categorySlug = null;
    public $currentCategory = null;

    public function updatedSelectedCategories()
    {
        $this->resetPage();
    }

    public function render()
    {
         $this->currentCategory = $this->categorySlug 
            ? Category::where('slug', $this->categorySlug)->first() 
            : null;

        return view('livewire.business.index', [
            'businesses' => $this->getOffersQuery()->paginate(20),
        ]);
    }

     private function getOffersQuery()
    {
        $targetIds = $this->currentCategory ? Category::getAllChildrenIds($this->currentCategory->id) : [];

        return Business::with(['categories', 'images'])
            ->when($this->categorySlug, fn($q) => $q->whereHas('categories', fn($query) => $query->whereIn('categories.id', $targetIds)))
            ->latest();
    }
}

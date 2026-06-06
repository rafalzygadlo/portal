<?php

namespace App\Livewire\Offer;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Offer;
use App\Models\Category;
use Illuminate\Http\Request;

class Index extends Component
{
    use WithPagination;
    //protected $paginationTheme = 'bootstrap';

    public $categorySlug = null;
    public $currentCategory = null;

    public function mount($categorySlug = null)
    {
        $this->categorySlug = $categorySlug;
    }

    public function render()
    {
        $this->currentCategory = $this->categorySlug 
            ? Category::where('slug', $this->categorySlug)->first() 
            : null;

        return view('livewire.offer.index', [
            'offers' => $this->getOffersQuery()->paginate(12),
        ]);
    }

    private function getOffersQuery()
    {
        $targetIds = $this->currentCategory ? Category::getAllChildrenIds($this->currentCategory->id) : [];

        return Offer::with(['user', 'categories', 'images'])
            ->when($this->categorySlug, fn($q) => $q->whereHas('categories', fn($query) => $query->whereIn('categories.id', $targetIds)))
            ->latest();
    }
}

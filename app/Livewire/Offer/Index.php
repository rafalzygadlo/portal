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

    public $categorySlug = null;
    public $perPage = 10;

    protected $listeners = [
        'offer-category-selected' => 'selectCategory',
    ];

    public function loadMore()
    {
        $this->perPage += 10;
    }

    public function selectCategory($slug = null)
    {
        $this->categorySlug = $slug;
        $this->resetPage();
    }


    private function getQuery(?Category $currentCategory)
    {
        $targetIds = $currentCategory ? Category::getAllChildrenIds($currentCategory->id) : [];

        return Offer::with(['user', 'categories', 'images'])
            ->when($this->categorySlug, fn($q) => $q->whereHas('categories', fn($query) => $query->whereIn('categories.id', $targetIds)))
            ->latest();
    }

    public function render()
    {
        $currentCategory = $this->categorySlug
            ? Category::where('slug', $this->categorySlug)->first() 
            : null;

        return view('livewire.offer.index', [
            'currentCategory' => $currentCategory,
            'offers' => $this->getQuery($currentCategory)->paginate($this->perPage),
        ]);
    }

   
}

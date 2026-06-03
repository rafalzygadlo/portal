<?php

namespace App\Livewire\Offer;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Offer\Offer;
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
        $targetIds = $this->currentCategory ? $this->getAllCategoryIds($this->currentCategory->id) : [];

        return Offer::with(['user', 'categories', 'images'])
            ->when($this->categorySlug, fn($q) => $q->whereHas('categories', fn($query) => $query->whereIn('categories.id', $targetIds)))
            ->latest();
    }

    private function getAllCategoryIds($parentId)
    {
        $ids = [$parentId];
        $children = Category::where('parent_id', $parentId)->pluck('id')->toArray();

        foreach ($children as $childId) {
            $ids = array_merge($ids, $this->getAllCategoryIds($childId));
        }

        return $ids;
    }
}

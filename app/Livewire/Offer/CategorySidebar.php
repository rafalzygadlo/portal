<?php

namespace App\Livewire\Offer;

use Livewire\Component;
use App\Models\Category;
use App\Models\Offer\Offer;

class CategorySidebar extends Component
{
    public $categorySlug;
    public $currentCategory;

    public function mount($categorySlug = null, $currentCategory = null)
    {
        $this->categorySlug = $categorySlug;
        $this->currentCategory = $currentCategory;
    }

    public function render()
    {
        $categories = $this->getSidebarCategories();
        $this->attachRecursiveOfferCounts($categories);

        return view('livewire.offer.category', [
            'categories' => $categories
        ]);
    }

    private function getSidebarCategories()
    {
        if (!$this->currentCategory) {
            return Category::whereNull('parent_id')->withCount('children')->get();
        }

        $subCategories = Category::where('parent_id', $this->currentCategory->id)->withCount('children')->get();

        return $subCategories->isNotEmpty() 
            ? $subCategories 
            : Category::where('parent_id', $this->currentCategory->parent_id)->withCount('children')->get();
    }

    private function attachRecursiveOfferCounts($categories)
    {
        foreach ($categories as $item) {
            $allChildIds = $this->getAllCategoryIds($item->id);
            $item->total_offers_recursive = Offer::whereHas('categories', fn($q) => $q->whereIn('categories.id', $allChildIds))->count();
        }
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
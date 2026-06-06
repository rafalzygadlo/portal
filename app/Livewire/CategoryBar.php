<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Category;

class CategoryBar extends Component
{
    public $categorySlug;
    public $currentCategory;
    public $modelClass; // Pełna nazwa klasy modelu, np. \App\Models\Offer

    public $route; // Nazwa trasy do generowania linków, np. 'offers.index'

    public function mount($route = null, $categorySlug = null, $currentCategory = null, $modelClass = null)
    {
        $this->route = $route;
        $this->categorySlug = $categorySlug;
        $this->currentCategory = $currentCategory;
        $this->modelClass = $modelClass;
    }

    public function render()
    {
        $categories = $this->getSidebarCategories();
        
        if ($this->modelClass) {
            $this->attachRecursiveCounts($categories);
        }

        return view('livewire.sidebar', [
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

    private function attachRecursiveCounts($categories)
    {
        foreach ($categories as $item) {
            $allChildIds = Category::getAllChildrenIds($item->id);
            $item->recursive_count = $this->modelClass::whereHas('categories', fn($q) => $q->whereIn('categories.id', $allChildIds))->count();
        }
    }
}
<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Category;

class CategoryBar extends Component
{

    public $currentCategory;

    public string $selectEvent;
    public string $orientation = 'vertical';


    public function render()
    {
        $categories = $this->getSidebarCategories();

        return view('livewire.category-bar', [
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


}
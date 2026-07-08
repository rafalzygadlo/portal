<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Modelable;
use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;

class CategorySelector extends Component
{
    public ?int $parentId = null;

    #[Modelable]
    public array $value = [];

    // Properties to replace computed ones
    public Collection $selectedCategoriesCollection;
    public ?Category $currentCategoryModel = null;

    public function mount()
    {
        // Manually initialize the collections on mount
        $this->updateSelectedCategories();
        $this->updateCurrentCategory();
    }

    public function selectCategory($id)
    {
        $category = Category::findOrFail($id);

        // If category has children, navigate into it
        if ($category->children()->exists()) {
            $this->parentId = $id;
            $this->updateCurrentCategory();
            return;
        }

        // If category is a leaf node, toggle its selection
        if (in_array($id, $this->value)) {
            // Remove from selection
            $this->value = array_filter($this->value, fn($selectedId) => $selectedId !== $id);
        } else {
            // Add to selection
            $this->value[] = $id;
        }

        // Manually update the selected categories collection
        $this->updateSelectedCategories();
    }

    public function goBack()
    {
        if (!$this->parentId) {
            return;
        }

        $this->parentId = Category::find($this->parentId)?->parent_id;
        $this->updateCurrentCategory();
    }

    // Helper method to update selected categories
    protected function updateSelectedCategories(): void
    {
        if (empty($this->value)) {
            $this->selectedCategoriesCollection = new Collection();
            return;
        }
        $this->selectedCategoriesCollection = Category::whereIn('id', $this->value)->get();
    }

    // Helper method to update the current category
    protected function updateCurrentCategory(): void
    {
        $this->currentCategoryModel = $this->parentId ? Category::find($this->parentId) : null;
    }

    public function render()
    {
        return view('livewire.category-selector', [
            'categories' => Category::where('parent_id', $this->parentId)->get()
        ]);
    }
}
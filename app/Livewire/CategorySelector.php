<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Reactive;
use App\Models\Category;


class CategorySelector extends Component
{
    public $parentId = null; // Aktualny poziom
    public $selectedCategoryId = null; // Wybrany liść
    public $name = null; // Przechowuje nazwę wybranej kategorii
    
    #[Reactive]
    public ?string $error = null; // Przyjmuje błąd z komponentu nadrzędnego

    public function selectCategory($id)
    {
        $category = Category::withCount('children')->find($id);

        if ($category->children_count > 0) 
        {    
            $this->parentId = $id;
        } 
        else 
        {
            $this->selectedCategoryId = $id;
            $this->name = $category->name; // Przechowujemy nazwę wybranej kategorii
            $this->dispatch('categorySelected', $id);
        }
    }

    public function goBack()
    {
        if ($this->parentId) {
            $parent = Category::find($this->parentId);
            $this->parentId = $parent->parent_id;
        }
    }

    public function submit() 
    {
        $this->validate();
    }

    public function render()
    {
        return view('livewire.category-selector', [
            'categories' => Category::where('parent_id', $this->parentId)->get()
        ]);
    }
}

<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Modelable;
use App\Models\Category;

class CategorySelector extends Component
{
    public ?int $parentId = null;

    #[Modelable]
    public ?int $value = null;

    public ?string $name = null;

    public function mount($value = null)
    {
        if($value != null)
            $this->selectCategory($value);
    }

    public function selectCategory($id)
    {
        $category = Category::findOrFail($id);

        if ($category->children()->exists()) {
            $this->parentId = $id;
            return;
        }

        $this->value = $id;
        $this->name = $category->name;
    }

    public function goBack()
    {
        if (!$this->parentId) {
            return;
        }

        $this->parentId = Category::find($this->parentId)?->parent_id;
    }

    public function render()
    {
        return view('livewire.category-selector', [
            'categories' => Category::where('parent_id', $this->parentId)->get()
        ]);
    }
}
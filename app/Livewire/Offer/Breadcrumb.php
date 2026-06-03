<?php

namespace App\Livewire\Offer;

use Livewire\Component;
use App\Models\Category;

class Breadcrumb extends Component
{
    public $category;
    public $path = [];

    public function mount($category = null)
    {
        if ($category) {
            $this->buildPath($category);
        }
    }

    private function buildPath($category)
    {
        $path = collect();
        while ($category) {
            $path->prepend($category);
            $category = $category->parent;
        }
        $this->path = $path->all();
    }

    public function render()
    {
        return view('livewire.offer.breadcrumb');
    }
}
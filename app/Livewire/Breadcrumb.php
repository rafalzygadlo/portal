<?php

namespace App\Livewire;


use Livewire\Component;


class Breadcrumb extends Component
{
    public $category;
    public $path = [];

    public function mount($category = null)
    {
        if ($category) {
            $this->path = $category->getBreadcrumbs()->all();
        }
    }

    public function render()
    {
        return view('livewire.breadcrumb');
    }
}
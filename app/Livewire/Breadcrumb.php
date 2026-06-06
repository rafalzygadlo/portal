<?php

namespace App\Livewire;


use Livewire\Component;


class Breadcrumb extends Component
{
    public $category;
    public $path = [];
    public $route;

    public function mount($route, $category = null)
    {
        $this->route = $route;
        if ($category) {
            $this->path = $category->getBreadcrumbs()->all();
        }
    }

    public function render()
    {
        return view('livewire.breadcrumb');
    }
}
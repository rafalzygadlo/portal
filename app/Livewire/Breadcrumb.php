<?php

namespace App\Livewire;


use Livewire\Component;


class Breadcrumb extends Component
{
    public $category;
    public $path = [];
    //public string $selectEvent;
    public $module;
    public function mount()
    {
       
        if ($this->category) {
            $this->path = $this->category->getBreadcrumbs()->all();
        }
    }

    public function render()
    {
        return view('livewire.breadcrumb');
    }
}
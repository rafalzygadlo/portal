<?php

namespace App\Livewire;


use Livewire\Component;


class Breadcrumb extends Component
{
    public $category;
    public $path = [];
    public string $selectEvent;

    public function mount($selectEvent, $category = null)
    {
        $this->selectEvent = $selectEvent;
        if ($category) {
            $this->path = $category->getBreadcrumbs()->all();
        }
    }

    public function render()
    {
        return view('livewire.breadcrumb');
    }
}
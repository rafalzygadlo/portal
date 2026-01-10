<?php

namespace App\Livewire;

use Livewire\Component;

class Page extends Component
{
    public $page;

    public function mount($page)
    {
        $this->page = $page;
    }
    public function render()
    {
         $view = "livewire.{$this->page}";
        if (!view()->exists($view)) 
        {
            abort(404);
        }

        return view($view);
        
    }

    }

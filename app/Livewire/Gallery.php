<?php

namespace App\Livewire;

use Livewire\Component;

class Gallery extends Component
{
    public $images;
    public $activeImage;

    public function mount($images)
    {
        $this->images = $images;
        if ($images->isNotEmpty()) 
            $this->activeImage = $images->first()->path;
        else
            $this->activeImage = null;
        
    }

    public function setActiveImage($path)
    {
        $this->activeImage = $path;
    }

    public function render()
    {
        return view('livewire.gallery');
    }
}
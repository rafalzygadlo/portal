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
        $this->activeImage = $images->first()->path;
    }

    public function setActiveImage($id)
    {
        $image = $this->images->firstWhere('id', $id);
        if ($image) {
            $this->activeImage = $image->path;
        }
    }

    public function render()
    {
        return view('livewire.gallery');
    }
}
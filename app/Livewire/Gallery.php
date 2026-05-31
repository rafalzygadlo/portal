<?php

namespace App\Livewire;

use Livewire\Component;

class Gallery extends Component
{
    public $images;
    public $activeImage;
    public $currentIndex = 0; 

    public function mount($images)
    {
        $this->images = $images;
        if ($images->isNotEmpty()) 
            $this->activeImage = $images->first()->path;
        else
            $this->activeImage = null;
        
    }

    public function nextImage()
    {
        if ($this->currentIndex < count($this->images) - 1) {
            $this->currentIndex++;
        } else {
            $this->currentIndex = 0; // Zapętlenie do początku
        }
    }

    public function prevImage()
    {
        if ($this->currentIndex > 0) {
            $this->currentIndex--;
        } else {
            $this->currentIndex = count($this->images) - 1; // Zapętlenie do końca
        }
    }

    // Helper, żeby łatwo dostać ścieżkę aktualnego zdjęcia
    public function getActiveImagePathProperty()
    {
        return $this->images[$this->currentIndex]->path;
    }


    public function render()
    {
        return view('livewire.gallery');
    }
}
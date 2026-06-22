<?php

namespace App\Livewire\Upload;

use Livewire\Attributes\Modelable;
use Livewire\Component;
use Illuminate\Support\Facades\Storage;

class Gallery extends Component
{
    #[Modelable]
    public array $files = [];

    public array $existingPhotos = [];
    public string $inputId = 'gallery-upload';
    public int $maxPhotos = 10;
    public string $title = 'Photos';
    public ?string $field = null;
    public array $errorFields = [];
    public ?string $subtitle = null;
    public bool $showReorder = true;

    public function mount(
        string $inputId = 'gallery-upload',
        int $maxPhotos = 10,
        string $title = 'Photos',
        ?string $subtitle = null,
        bool $showReorder = true,
        ?string $field = null,
        array $existingPhotos = [],
        array $errorFields = []
    ): void {
        $this->inputId = $inputId;
        $this->maxPhotos = $maxPhotos;
        $this->title = $title;
        $this->subtitle = $subtitle;
        $this->showReorder = $showReorder;
        $this->field = $field;
        $this->existingPhotos = $existingPhotos;
        $this->errorFields = $errorFields;
    }

    // Usuwanie nowych plików
    public function removePhoto(int $index): void
    {
        unset($this->files[$index]);
        $this->files = array_values($this->files);
    }

    // Usuwanie istniejących (z bazy)
    public function removeExistingPhoto(int $index): void
    {
        $photoId = $this->existingPhotos[$index]['id'] ?? null;
        unset($this->existingPhotos[$index]);
        $this->existingPhotos = array_values($this->existingPhotos);
        $this->dispatch('photo-removed', photoId: $photoId);
    }

    // Logika przesuwania (używamy metody swap)
    public function moveExistingPhotoUp(int $index): void { $this->swap($this->existingPhotos, $index, $index - 1); }
    public function moveExistingPhotoDown(int $index): void { $this->swap($this->existingPhotos, $index, $index + 1); }
    public function movePhotoUp(int $index): void { $this->swap($this->files, $index, $index - 1); }
    public function movePhotoDown(int $index): void { $this->swap($this->files, $index, $index + 1); }

    private function swap(array &$array, int $a, int $b): void
    {
        if (isset($array[$a]) && isset($array[$b])) {
            $tmp = $array[$a];
            $array[$a] = $array[$b];
            $array[$b] = $tmp;
        }
    }

    public function render()
    {
        return view('livewire.upload.gallery', [
            'fileCount' => count($this->files) + count($this->existingPhotos)
        ]);
    }
}
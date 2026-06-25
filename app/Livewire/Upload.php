<?php

namespace App\Livewire;

use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Modelable;
use Illuminate\Support\Facades\Storage;

class Upload extends Component
{
    use WithFileUploads;

    #[Modelable]
    public array $allPhotos = [];
    public array $newUploads = [];
    public int $maxPhotos;
    public bool $showReorder;
    public array $errorFields;
    public string $validationRules;
    public array $existingPhotos = [];

    public function mount(
        string $inputId = 'gallery-upload',
        int $maxPhotos = 5,
        array $existingPhotos = [],
        array $errorFields = []
    ): void {
    
        $this->existingPhotos = $existingPhotos;
        $this->maxPhotos = $maxPhotos;
        $this->showReorder = true;
        $this->allPhotos = $existingPhotos;
        $this->errorFields = $errorFields;
        $this->validationRules = 'image|max:10240';
    }

    /**
     * This method is called when new files are selected.
     * It merges the new uploads with the existing `allPhotos` array.
     */
    public function updatedNewUploads(): void
    {
        $this->validate(['newUploads.*' => $this->validationRules]);

        $count = count($this->allPhotos) + count($this->newUploads);
        foreach ($this->newUploads as $index => $file) 
        {
      
                $this->allPhotos[] =  array(
                'id' => $index + $count,   
                'file' => $file,
                'path' => $file->temporaryUrl(),
                'isNew'=> true
                );
            
        }


        if (count($this->allPhotos) > $this->maxPhotos) {
            // Trim the array to respect the maxPhotos limit
            $this->allPhotos = array_slice($this->allPhotos, 0, $this->maxPhotos);

            // Optionally, add a custom error message to inform the user
            $this->addError('newUploads', trans('validation.max.array', ['attribute' => 'photos', 'max' => $this->maxPhotos]));
        }

        // Clear the temporary property.
        $this->newUploads = [];
    }

    // Usuwanie zdjęć (nowych i istniejących)
    public function removePhoto(int $index): void
    {
        
        unset($this->allPhotos[$index]);
        $this->allPhotos = array_values($this->allPhotos);
    }

    // Logika przesuwania (używamy metody swap)
    public function movePhotoUp(int $index): void
    {
        $this->swap($this->allPhotos, $index, $index - 1);
    }
    public function movePhotoDown(int $index): void
    {
        $this->swap($this->allPhotos, $index, $index + 1);
    }

    private function swap(array &$array, int $a, int $b): void
    {
        if (!isset($array[$a], $array[$b])) return;

        [$array[$a], $array[$b]] = [$array[$b], $array[$a]];
    }

    public function render()
    {
        return view('livewire.upload.gallery', [
            'fileCount' => count($this->allPhotos)
        ]);
    }
}
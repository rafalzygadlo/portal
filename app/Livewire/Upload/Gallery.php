<?php

namespace App\Livewire\Upload;

use App\Models\Photo;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

class Gallery extends Component
{
    use WithFileUploads;

    public array $allPhotos = [];

    // Temporary property for new file uploads to allow appending.
    #[Validate('image|max:10240')] // 10MB Max, validates each file.
    public array $newUploads = [];

    public string $inputId;
    public int $maxPhotos;
    public string $title;
    public ?string $subtitle;
    public bool $showReorder;
    public ?string $field;
    public array $errorFields;

    public function mount(
        string $inputId = 'gallery-upload',
        int $maxPhotos = 10,
        string $title = 'Photos',
        ?string $subtitle = null,
        bool $showReorder = false,
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
        $this->allPhotos = array_map(
            fn ($photo) => Photo::fromExisting($photo),
            $existingPhotos
        );
        $this->errorFields = $errorFields;
    }

    /**
     * This method is called when new files are selected.
     * It merges the new uploads with the existing `allPhotos` array.
     */
    public function updatedNewUploads(): void
    {
        $this->validate(['newUploads.*' => 'image|max:10240']);

        $newPhotos = array_map(
            fn ($upload) => Photo::fromTemporaryUpload($upload),
            $this->newUploads
        );

        $this->allPhotos = array_merge($this->allPhotos, $newPhotos);

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
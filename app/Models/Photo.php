<?php

namespace App\Models;

use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\Wireable;

class Photo implements Wireable // This is a non-persistent model (or a DTO/ViewModel)
{
    public readonly string $id;
    public readonly bool $isNew;

    private function __construct(
        public readonly TemporaryUploadedFile|array|string $source
    ) {
        $this->isNew = $this->source instanceof TemporaryUploadedFile;
        $this->id = $this->isNew ? $source->getFilename() : ($source['id'] ?? uniqid());
    }

    /**
     * "Teaches" Livewire how to serialize this object.
     */
    public function toLivewire(): array
    {
        return ['source' => $this->source];
    }

    /**
     * "Teaches" Livewire how to deserialize this object.
     *
     * @param array $value
     */
    public static function fromLivewire($value): self
    {
        return new self($value['source']);
    }

    /**
     * Tworzy obiekt Photo z nowo przesłanego pliku.
     */
    public static function fromTemporaryUpload(TemporaryUploadedFile $file): self
    {
        return new self($file);
    }

    /**
     * Tworzy obiekt Photo z istniejących danych (np. z bazy).
     */
    public static function fromExisting(array|string $data): self
    {
        
        return new self($data);
    }

    /**
     * Zwraca URL do wyświetlenia obrazka, niezależnie od jego typu.
     */
    public function getUrl(): string
    {
        if ($this->isNew) {
            return $this->source->temporaryUrl();
        }

        $path = is_array($this->source) ? ($this->source['path'] ?? null) : $this->source;

        if ($path) {
            return Storage::url($path);
        }
        return '';
    }

    /**
     * Returns the original database ID for existing photos.
     */
    public function getIdentifier(): ?int
    {
        if ($this->isNew || !is_array($this->source)) {
            return null;
        }
        return $this->source['id'] ?? null;
    }
}
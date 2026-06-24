<?php

namespace App\DataObjects;

use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class Photo
{
    public readonly string $id;
    public readonly bool $isNew;

    private function __construct(
        public readonly TemporaryUploadedFile|array|string $source
    ) {
        $this->isNew = $source instanceof TemporaryUploadedFile;
        $this->id = $this->isNew ? $source->getFilename() : ($source['id'] ?? uniqid());
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

        return is_array($this->source) ? ($this->source['url'] ?? '') : $this->source;
    }
}
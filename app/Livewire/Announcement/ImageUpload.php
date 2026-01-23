<?php

namespace App\Livewire\Announcement;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Announcement\Announcement;

class ImageUpload extends Component
{
    use WithFileUploads;

    public $announcement;
    public $images = [];
    public $existingImages = [];

    public function mount(Announcement $announcement)
    {
        $this->announcement = $announcement;
        $this->existingImages = $announcement->getMedia('announcement_images');
    }

    public function updatedImages()
    {
        $this->validate([
            'images.*' => 'image|max:1024', // 1MB Max
        ]);
    }

    public function saveImages()
    {
        $this->validate([
            'images.*' => 'image|max:1024', // 1MB Max
        ]);

        foreach ($this->images as $image) {
            $this->announcement->addMedia($image->getRealPath())
                               ->usingFileName($image->hashName())
                               ->toMediaCollection('announcement_images');
        }

        $this->images = []; // Clear uploaded images
        $this->existingImages = $this->announcement->fresh()->getMedia('announcement_images'); // Refresh existing images
        session()->flash('message', 'Images successfully uploaded.');
    }

    public function removeImage($mediaId)
    {
        $media = $this->announcement->getMedia('announcement_images')->find($mediaId);
        if ($media) {
            $media->delete();
            $this->existingImages = $this->announcement->fresh()->getMedia('announcement_images'); // Refresh
            session()->flash('message', 'Image removed.');
        }
    }

    public function render()
    {
        return view('livewire.announcement.image-upload');
    }
}

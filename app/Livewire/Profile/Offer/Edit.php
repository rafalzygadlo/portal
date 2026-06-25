<?php

namespace App\Livewire\Profile\Offer;

use App\Models\Category;
use App\Models\Image;
use App\Models\Offer;
use App\Services\OfferImageService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Collection;

class Edit extends Component
{
    use WithFileUploads, AuthorizesRequests;

    public Offer $offer;
    public string $title = '';
    public string $content = '';
    public ?int $category_id = null;
    public array $allPhotos;
    public array $initialData = [];

    public function mount(Offer $offer): void
    {
        //$this->authorize1('update', $offer);
    
        $this->offer = $offer;
        $this->title = $offer->title;
        $this->content = $offer->content;
        $this->category_id = $offer->categories()->first()?->id;
        
        $this->allPhotos = $offer->images()->get()->map(fn(Image $image) => [
            'id' => $image->id,
            'path' => Storage::url($image->path),
            'isNew' => false
        ])->toArray();

    }


    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'content' => 'required|string|max:5000',
            'category_id' => 'required|exists:categories,id',
            //'allPhotos.*' => 'nullable|image|max:8192',
        ];
    }


    public function save(OfferImageService $imageService)
    {

        //dd($this->allPhotos);
        $this->validate();
 
        $this->offer->update([
            'title' => $this->title,
            'content' => $this->content,
            'slug' => \Str::slug($this->title),
        ]);
        
        $this->offer->categories()->sync([$this->category_id]);

        dd($this->allPhotos);
        $existingPhotos = array_filter($this->allPhotos, 'is_array');
        $newPhotos = array_filter($this->allPhotos, fn($photo) => !is_array($photo));


        // 1. Logika usuwania zdjęć (usuwamy te, których nie ma już w $existingPhotos)
        $existingIds = array_column($existingPhotos, 'id');
        $originalIds = $this->offer->images()->pluck('id')->toArray();
        $idsToDelete = array_diff($originalIds, $existingIds);

        foreach ($idsToDelete as $imageId) {
            $image = $this->offer->images()->find($imageId);
            if ($image) {
                Storage::disk('public')->delete($image->path);
                $image->delete();
            }
        }

        // 2. Dodawanie nowych zdjęć
        if (!empty($newPhotos)) {
            $imageService->processAndAttach($this->offer, $newPhotos);
        }

        session()->flash('status', 'Oferta została zaktualizowana!');

      
    }

    public function render()
    {
        return view('livewire.profile.offer.edit', [
            'categories' => Category::whereNull('parent_id')->get(),
        ]);
    }
}
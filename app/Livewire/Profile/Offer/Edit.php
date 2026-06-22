<?php

namespace App\Livewire\Profile\Offer;

use App\Models\Category;
use App\Models\Offer;
use App\Services\OfferImageService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class Edit extends Component
{
    use WithFileUploads, AuthorizesRequests;

    public const MAX_PHOTOS = 10;

    public Offer $offer;
    public string $title = '';
    public string $content = '';
    public ?int $category_id = null;
    
    // Zgodnie z komponentem galerii:
    public array $existingPhotos = []; // Zdjęcia z bazy
    public array $photos = [];         // Nowe zdjęcia (tymczasowe)

    public function mount(Offer $offer): void
    {
        $this->authorize('update', $offer);

        $this->offer = $offer;
        $this->title = $offer->title;
        $this->content = $offer->content;
        $this->category_id = $offer->categories()->first()?->id;
        
        // Ładujemy istniejące zdjęcia
        $this->existingPhotos = $offer->images()->get()->toArray();
    }

    public function updatedPhotos(): void
    {
        $this->resetErrorBag('photos');

        // Walidacja tylko dla nowych plików
        $this->validate([
            'photos.*' => 'image|max:8192',
        ]);

        $totalCount = count($this->existingPhotos) + count($this->photos);
        if ($totalCount > self::MAX_PHOTOS) {
            $this->addError('photos', 'Maksymalnie ' . self::MAX_PHOTOS . ' zdjęć łącznie.');
            // Przycinamy tablicę nowych zdjęć do limitu
            $this->photos = array_slice($this->photos, 0, max(0, self::MAX_PHOTOS - count($this->existingPhotos)));
        }
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'content' => 'required|string|max:5000',
            'category_id' => 'required|exists:categories,id',
            'photos.*' => 'nullable|image|max:8192',
        ];
    }

    public function save(OfferImageService $imageService)
    {
        $this->validate();

        $this->offer->update([
            'title' => $this->title,
            'content' => $this->content,
            'slug' => \Str::slug($this->title),
        ]);

        $this->offer->categories()->sync([$this->category_id]);

        // 1. Logika usuwania zdjęć (usuwamy te, których nie ma już w $existingPhotos)
        $existingIds = array_column($this->existingPhotos, 'id');
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
        if (!empty($this->photos)) {
            $imageService->processAndAttach($this->offer, $this->photos);
        }

        session()->flash('status', 'Oferta została zaktualizowana!');
        //return $this->redirect(route('user.profile'));
    }

    public function render()
    {
        return view('livewire.profile.offer.edit', [
            'categories' => Category::whereNull('parent_id')->get(),
        ]);
    }
}
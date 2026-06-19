<?php

namespace App\Livewire\Offer;

use Livewire\Component;
use App\Models\Offer;
use App\Models\Category;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use App\Services\OfferImageService;
use App\Services\ImageAnalysisService;

class Create extends Component
{
    use WithFileUploads;

    public string $title = '';
    public string $content = '';    
    public ?int $category_id = null;
    public $photos = [];
    public $newPhotos = [];
    
    
    public function updatedNewPhotos()
    {
        $this->validate([
            'newPhotos.*' => 'image|max:8192',
        ]);

        foreach ($this->newPhotos as $photo) {
            $this->photos[] = $photo;
        }
    }

    public function mount(Offer $offer = null)
    {
        if ($offer) 
        {
            $this->title = $offer->title;
            $this->content = $offer->content;
            $this->category_id = $offer->categories()->first()?->id;
            //$this->photos = $offer->images()->pluck('path')->toArray();
        }
    }

    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'content' => 'required|string|max:5000',
            'category_id' => 'required|exists:categories,id',
            'photos.*' => 'required|image|max:8192', // 8MB Max per photo
        ];
    }

    public function removePhoto($index)
    {
        unset($this->photos[$index]);
        $this->photos = array_values($this->photos);
    }
    

    /**
     * Save the offer and process images.
     * 
     * @param OfferImageService $imageService
     */
    public function save(OfferImageService $imageService)
    {

        $this->validate();

        $offer = Offer::create([
            'user_id' => auth()->id(),
            'title' => $this->title,
            'content' => $this->content,
            'slug' => \Str::slug($this->title),
        ]);

        $offer->categories()->attach($this->category_id);

        $imageService->processAndAttach($offer, $this->photos);

        return $this->redirect('/offers');
    }

    public function render()
    {
        $categories = Category::whereNull('parent_id')->get();

        return view('livewire.offer.create', [
            'categories' => $categories
        ]);
    }
}

<?php

namespace App\Livewire\Offer;

use Livewire\Component;
use App\Models\Offer\Offer;
use App\Models\Category;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use App\Services\OfferImageService;

class Create extends Component
{
    use WithFileUploads;

    public string $title = '';
    public string $content = '';    
    public int $category_id = 0;
    public $photos = [];
    public bool $open = false;

    protected $listeners = 
    [
        'openOfferModal',
        'closeOfferModal', 
        'saveOffer'
    ];
    
    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'content' => 'required|string|max:5000',
            'category_id' => 'required|exists:categories,id',
            'photos.*' => 'required|image|max:8192', // 8MB Max per photo
        ];
    }

    public function openOfferModal()
    {
        $this->open = true;
    }

    public function closeOfferModal()
    {
        $this->open = false;
        $this->reset('title', 'content', 'category_id', 'photos');
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
        $categories = Category::orderBy('name')->get();

        return view('livewire.offer.create', [
            'categories' => $categories
        ]);
    }
}

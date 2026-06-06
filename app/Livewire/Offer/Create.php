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
    
    protected $listeners = 
    [
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

    public function removePhoto($index)
    {
        unset($this->photos[$index]);
        $this->photos = array_values($this->photos);
    }

    /**
     * Analyze first photo to auto-fill title and description
     */
    public function analyzePhoto()
    {
        if (empty($this->photos)) {
            $this->addError('photos', 'Wrzuć co najmniej jedno zdjęcie');
            return;
        }

        try {
            $imageService = new ImageAnalysisService();
            $result = $imageService->analyzeImage($this->photos[0]);

            if (isset($result['error'])) {
                $this->addError('photos', 'Nie udało się przeanalizować zdjęcia: ' . $result['error']);
                return;
            }

            // Auto-fill fields
            if (!empty($result['title'])) {
                $this->title = $result['title'];
            }

            if (!empty($result['description'])) {
                $this->content = $result['description'];
            }

            $this->dispatch('notify', message: '✨ Zdjęcie przeanalizowane! Tytuł i opis zostały uzupełnione.');

        } catch (\Exception $e) {
            \Log::error('Photo analysis error: ' . $e->getMessage());
            $this->addError('photos', 'Błąd przy analizie zdjęcia');
        }
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

<?php

namespace App\Livewire\Profile\Offer;

use Livewire\Component;
use App\Models\Offer;
use App\Models\Category;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use App\Services\OfferImageService;
use App\Services\ImageAnalysisService;

class Create extends Component
{
    

    public string $title = '';
    public string $content = '';    
    public ?int $category_id = null;
    public array $allPhotos = [];

  

    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'content' => 'required|string|max:5000',
            'category_id' => 'required|exists:categories,id',
            //'allPhotos.*' => 'required|image|max:8192',
        ];
    }

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
        
        $imageService->processAndAttach($offer, $this->allPhotos);

        session()->flash('status', 'Oferta została dodana!');
        return $this->redirect(route('user.profile'));
    }

    public function render()
    {
        $categories = Category::whereNull('parent_id')->get();

        return view('livewire.profile.offer.create', [
            'categories' => $categories
        ]);
    }
}

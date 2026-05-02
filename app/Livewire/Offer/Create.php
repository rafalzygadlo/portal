<?php

namespace App\Livewire\Offer;

use Livewire\Component;
use App\Models\Offer\Offer;
use App\Models\Category;
use Livewire\WithFileUploads;

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

    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'content' => 'required|string|max:5000',
            'category_id' => 'required|exists:categories,id',
            'photos.*' => 'image|max:2048', // 2MB Max per photo
        ];
    }

    public function save()
    {
        $this->validate();

        $offer = Offer::create([
            'user_id' => auth()->id(),
            'title' => $this->title,
            'content' => $this->content,
        ]);

        $offer->categories()->attach($this->category_id);

        foreach ($this->photos as $photo) {
            $path = $photo->store('offers', 'public');
            $offer->images()->create(['path' => $path]); // Używa relacji morphMany
        }

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

<?php

namespace App\Livewire\Offer;

use Livewire\Component;
use App\Models\Offer\Offer;
use App\Models\Offer\Category;

class Create extends Component
{
    public string $title = '';
    public string $content = '';
    public int $offer_category_id = 0;

    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'content' => 'required|string|max:5000',
            'offer_category_id' => 'required|exists:offer_categories,id',
        ];
    }

    public function save()
    {
        $this->validate();

        Offer::create([
            'user_id' => auth()->id(),
            'title' => $this->title,
            'content' => $this->content,
            'offer_category_id' => $this->offer_category_id,
        ]);

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

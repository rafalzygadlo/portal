<?php

namespace App\Livewire\Offer;

use Livewire\Component;
use App\Models\Offer\Offer;
use App\Models\Category;

class Create extends Component
{
    public string $title = '';
    public string $content = '';
    public int $category_id = 0;

    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'content' => 'required|string|max:5000',
            'category_id' => 'required|exists:categories,id',
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
        $offer->save();

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

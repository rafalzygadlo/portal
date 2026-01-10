<?php

namespace App\Livewire\Article;

use Livewire\Component;
use App\Models\Article\Article;
use App\Models\Article\Category;
use Illuminate\Support\Facades\Auth;
use Livewire\WithFileUploads;

class Form extends Component
{
    use WithFileUploads;

    public $title;
    public $content;
    public $photo;
    public $category_id;

    protected $rules = [
        'title' => 'required|min:5|max:255',
        'content' => 'required|min:10',
        'photo' => 'nullable|image|max:2048', // Maksymalnie 2MB
        //'category_id' => 'exists:article_categories,id',
    ];

    public function save()
    {
        $this->validate();

        $imagePath = null;
        if ($this->photo) {
            $imagePath = $this->photo->store('articles', 'public');
        }

        Article::create([
            'user_id' => Auth::id(),
            'category_id' => $this->category_id,
            'title' => $this->title,
            'content' => $this->content,
            'image_path' => $imagePath,
        ]);

        return redirect()->to('/')->with('status', 'Artykuł został dodany i czeka na publikację!');
    }

    public function render()
    {
        return view('livewire.article.form', [
            'categories' => Category::where('slug', '!=', 'spam')->get()
        ]);
    }
}
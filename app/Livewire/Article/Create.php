<?php

namespace App\Livewire\Article;

use Livewire\Component;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Livewire\WithFileUploads;
use Illuminate\Validation\ValidationException;

class Create extends Component
{
    use WithFileUploads;

    public $title;
    public $content;
    public $photos = [];
    public $categories = [];
    public $mode = 'edit';
    public $honey_pot;

    protected $rules = [
        'title' => 'required|min:5|max:255',
        'content' => 'required|min:10',
        'photos.*' => 'nullable|image|max:2048', // Maksymalnie 2MB
        'categories' => 'required|array|min:1',
    ];

    
    public function preview()
    {
        // Optional: validation before showing preview
        $this->validate([
            'title' => 'required|min:5',
            'content' => 'required|min:10',
            
        ]);
    
        $this->mode = 'preview';
    }
    
    public function edit()
    {
        $this->mode = 'edit';
    }
    
    public function save()
    {
        if(!empty($this->honey_pot)) {
            return null;
        }
        $this->validate();

        foreach ($this->photos as $photo) 
        {
            $filename = $photo->hashName();

            // W wersji 2 używamy make() oraz resize() z funkcją zachowania proporcji
            $img = Image::make($photo->getRealPath())->resize(1200, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });

            // Enkodujemy do formatu JPG i zapisujemy
            Storage::disk('public')->put('articles/' . $filename, (string) $img->encode('jpg', 80));
            $article->images()->create(['path' => 'articles/' . $filename]);
        }

        $article = Article::create([
            'user_id' => Auth::id(),
            'title' => $this->title,
            'content' => $this->content
        ]);

        $article->categories()->sync($this->categories);

        return redirect()->to('/')->with('status', 'Your article has been added and is pending publication!');
    }

    public function render()
    {
        return view('livewire.article.create', [
            'allCategories' => Category::where('slug', '!=', 'spam')->get()
        ]);
    }
}
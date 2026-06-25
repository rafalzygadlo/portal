<?php

namespace App\Livewire\Profile\Article;

use Livewire\Component;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Livewire\WithFileUploads;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\Storage;

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
        'photos.*' => 'nullable|image|max:2048',
        'categories' => 'required|array|min:1',
    ];

    public function preview()
    {
        $this->validate([
            'title' => 'required|min:5',
            'content' => 'required|min:10',
        ]);
    
        $this->mode = 'preview';
    }
    
    public function save()
    {
        if(!empty($this->honey_pot)) {
            return null;
        }
        $this->validate();

        $article = Article::create([
            'user_id' => Auth::id(),
            'title' => $this->title,
            'content' => $this->content
        ]);

        foreach ($this->photos as $photo) 
        {
            $filename = $photo->hashName();
            $img = \Intervention\Image\ImageManager::gd()->read($photo->getRealPath())->resize(1200, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });

            Storage::disk('public')->put('articles/' . $filename, (string) $img->encode('jpg', 80));
            $article->images()->create(['path' => 'articles/' . $filename]);
        }

        $article->categories()->sync($this->categories);

        session()->flash('status', 'Artykuł został dodany!');
        return $this->redirect(route('user.profile'));
    }

    public function render()
    {
        return view('livewire.profile.article.create', [
            'allCategories' => Category::where('slug', '!=', 'spam')->get()
        ]);
    }
}

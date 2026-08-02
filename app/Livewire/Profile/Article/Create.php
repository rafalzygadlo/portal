<?php

namespace App\Livewire\Profile\Article;

use Livewire\Component;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Livewire\WithFileUploads;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Format;
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
    public $photo;

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

        $manager = new ImageManager(new Driver());

        foreach ($this->photos as $photo) 
        {
            $filename = $photo->hashName();
            $image = $manager->read($photo->getRealPath());
            $image->scaleDown(width: 1200);
            $encoded = $image->encodeUsingFormat(Format::JPEG, quality: 80);

            Storage::disk('public')->put('articles/' . $filename, $encoded);
            $article->images()->create(['path' => 'articles/' . $filename]);
        }

        $article->categories()->sync($this->categories);

        session()->flash('status', 'Artykuł został dodany!');
        return $this->redirect(route('user.profile'));
    }

    public function render()
    {
        return view('livewire.article.create', [
            'allCategories' => Category::where('slug', '!=', 'spam')->get()
        ]);
    }
}

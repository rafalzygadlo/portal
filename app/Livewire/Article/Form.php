<?php

namespace App\Livewire\Article;

use Livewire\Component;
use App\Models\Article\Article;
use App\Models\Article\Category;
use Illuminate\Support\Facades\Auth;
use Livewire\WithFileUploads;
use Illuminate\Validation\ValidationException;

class Form extends Component
{
    use WithFileUploads;

    public $title;
    public $content;
    public $photo;
    public $category_id;
    public $mode = 'edit';
    public $honey_pot;

    protected $rules = [
        'title' => 'required|min:5|max:255',
        'content' => 'required|min:10',
        'photo' => 'nullable|image|max:2048', // Maksymalnie 2MB
        //'category_id' => 'exists:article_categories,id',
    ];

    private function containsForbiddenWords($text) {
        $forbiddenWords = ['kurwa', 'cholera', 'chuj', 'pierdol'];
        foreach ($forbiddenWords as $word) {
            if (stripos($text, $word) !== false) {
                return true;
            }
        }
        return false;
    }

    
    public function preview()
    {
        // Opcjonalnie: walidacja przed pokazaniem podglądu
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

        if ($this->containsForbiddenWords($this->title) || $this->containsForbiddenWords($this->content)) {
            throw ValidationException::withMessages([
                'content' => 'Treść zawiera niedozwolone słowa.',
            ]);
        }

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
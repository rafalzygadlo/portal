<?php

namespace App\Livewire\Profile\Article;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Format;
use Intervention\Image\ImageManager;
use Livewire\Component;
use Livewire\WithFileUploads;

class Edit extends Component
{
    use WithFileUploads, AuthorizesRequests;

    public const MAX_PHOTOS = 10;

    public Article $article;
    public $title;
    public $content;
    public $categories = [];
    public $photos = [];
    public $mode = 'edit';
    public array $existingPhotos = [];

    public function mount(Article $article)
    {
        $this->authorize('update', $article);

        $this->article = $article;
        $this->title = $article->title;
        $this->content = $article->content;
        $this->categories = $article->categories()->pluck('id')->toArray();
        $this->existingPhotos = $article->images()->get()->toArray();
    }

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

    public function edit()
    {
        $this->mode = 'edit';
    }

    public function save()
    {
        $this->validate();

     
        $this->article->update([
            'title' => $this->title,
            'content' => $this->content,
        ]);

        // Usunąć zdjęcia których już nie ma w existingPhotos
        $existingIds = array_map(fn($photo) => is_array($photo) ? ($photo['id'] ?? null) : ($photo->id ?? null), $this->existingPhotos);
        $existingIds = array_filter($existingIds, fn($id) => $id !== null);
        $originalIds = $this->article->images()->pluck('id')->toArray();
        $idsToDelete = array_diff($originalIds, $existingIds);

        foreach ($idsToDelete as $imageId) {
            $image = $this->article->images()->whereKey($imageId)->first();
            if ($image) {
                if (is_string($image->path) && $image->path !== '') {
                    Storage::disk('public')->delete($image->path);
                }
                $image->delete();
            }
        }

        $manager = new ImageManager(new Driver());

        foreach ($this->photos as $photo) {
            $filename = $photo->hashName();
            $image = $manager->read($photo->getRealPath());
            $image->scaleDown(width: 1200);
            $encoded = $image->encodeUsingFormat(Format::JPEG, quality: 80);

            Storage::disk('public')->put('articles/' . $filename, $encoded);
            $this->article->images()->create(['path' => 'articles/' . $filename]);
        }

        $this->article->categories()->sync($this->categories);

        session()->flash('status', 'Artykuł został zaktualizowany!');
        return $this->redirect(route('user.profile'));
    }

    public function delete()
    {
        $this->authorize('delete', $this->article);
        $this->article->delete();

        session()->flash('status', 'Artykuł został usunięty!');
        return $this->redirect(route('user.profile'));
    }

    public function render()
    {
        return view('livewire.profile.article.edit', [
            'allCategories' => Category::where('slug', '!=', 'spam')->get(),
            'existingPhotos' => $this->existingPhotos,
        ]);
    }
}


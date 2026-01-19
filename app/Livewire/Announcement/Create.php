<?php

namespace App\Livewire\Announcement;

use App\Models\Announcement\Announcement;
use App\Models\Announcement\AnnouncementCategory;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class Create extends Component
{
    use WithFileUploads;

    public string $title = '';
    public string $content = '';
    public int $announcement_category_id = 0;
    public ?string $category_slug = null;
    public ?string $salary = null;
    public ?float $price = null;
    public array $photos = [];

    public function rules(): array
    {
        $rules = [
            'title' => 'required|min:3|max:255',
            'content' => 'required|min:10',
            'announcement_category_id' => 'required|exists:announcement_categories,id',
            'photos.*' => 'image|max:1024', // 1MB Max
        ];

        if ($this->category_slug === 'praca') {
            $rules['salary'] = 'required|string|max:255';
        }

        if ($this->category_slug === 'motoryzacja') {
            $rules['price'] = 'required|numeric';
        }

        return $rules;
    }

    public function save()
    {
        $this->validate();

        $announcement = Announcement::create([
            'user_id' => Auth::id(),
            'title' => $this->title,
            'content' => $this->content,
            'announcement_category_id' => $this->announcement_category_id,
            'price' => $this->price,
            'salary' => $this->salary,
        ]);

        foreach ($this->photos as $photo) {
            $path = $photo->store('announcements', 'public');
            $announcement->photos()->create(['path' => $path]);
        }

        session()->flash('status', 'Dziękujemy za dodanie ogłoszenia.');

        return $this->redirect('/announcements');
    }
    
    public function updatedAnnouncementCategoryId($value)
    {
        $category = AnnouncementCategory::find($value);
        $this->category_slug = $category ? $category->slug : null;
    }

    public function render()
    {
        $categories = AnnouncementCategory::orderBy('name')->get();
        
        return view('livewire.announcement.create', [
            'categories' => $categories
        ]);
    }
}

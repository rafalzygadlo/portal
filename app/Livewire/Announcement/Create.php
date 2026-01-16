<?php

namespace App\Livewire\Announcement;

use App\Models\Announcement\Announcement;
use App\Models\Announcement\AnnouncementCategory;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Create extends Component
{
    public string $title = '';
    public string $content = '';
    public int $announcement_category_id = 0;

    protected array $rules = [
        'title' => 'required|min:3|max:255',
        'content' => 'required|min:10',
        'announcement_category_id' => 'required|exists:announcement_categories,id',
    ];

    public function save()
    {
        $this->validate();

        Announcement::create([
            'user_id' => Auth::id(),
            'title' => $this->title,
            'content' => $this->content,
            'announcement_category_id' => $this->announcement_category_id,
        ]);

        session()->flash('status', 'Dziękujemy za dodanie ogłoszenia.');

        return $this->redirect('/announcements');
    }

    public function render()
    {
        $categories = AnnouncementCategory::orderBy('name')->get();
        
        return view('livewire.announcement.create', [
            'categories' => $categories
        ]);
    }
}

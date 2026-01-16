<?php

namespace App\Livewire\Announcement;

use Livewire\Component;
use App\Models\Announcement\Announcement;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public function render()
    {
        $announcements = Announcement::with('user', 'category')->latest()->paginate(10);

        return view('livewire.announcement.index', [
            'announcements' => $announcements
        ]);
    }
}

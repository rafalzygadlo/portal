<?php

namespace App\Livewire\Announcement;

use Livewire\Component;
use App\Models\Announcement\Announcement;

class Show extends Component
{
    public Announcement $announcement;

    public function mount(Announcement $announcement)
    {
        $this->announcement = $announcement;
    }

    public function render()
    {
        return view('livewire.announcement.show');
    }
}

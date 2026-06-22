<?php

namespace App\Livewire\Profile;

use Livewire\Component;
use App\Models\User;

class Index extends Component
{
    public User $user;
    public string $activeTab = 'overview';

    public function mount()
    {
        $this->user = auth()->user()->loadMissing([
            'ownedBusinesses',
            'offers',
            'articles',
            'polls',
            'comments',
            'favorites'
        ]);
    }

    public function switchTab(string $tab)
    {
        $this->activeTab = $tab;
    }

    public function render()
    {
        return view('livewire.profile.index');
    }
}

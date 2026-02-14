<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Article\Article;

class Profile extends Component
{
    public User $user;

    public function mount(User $user)
    {
        $this->user = $user;
        $this->user->load('ownedBusinesses');
    }

    public function render()
    {
       
        $reputation =  0;

        return view('livewire.profile', [
            'articles' => 0,
            'reputation' => $reputation
        ]);
    }
}
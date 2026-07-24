<?php

namespace App\Livewire;

use Livewire\Component;

class NavbarAuth extends Component
{
    public function openLoginModal()
    {
        $this->dispatch('openLoginModal');
    }

    public function render()
    {
        return view('livewire.navbar-auth');
    }
}

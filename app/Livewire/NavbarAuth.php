<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On; // Upewnij
class NavbarAuth extends Component
{
 
    #[On('loginSuccess')]
    public function refresh()
    {
        //dd();
        // Ta metoda zostanie wywołana po zalogowaniu.
        // Samo jej istnienie z atrybutem On spowoduje,
        // że Livewire odświeży ten komponent.
    }

  
    public function render()
    {
        return view('livewire.navbar-auth');
    }
}

<?php

namespace App\Livewire\Business\Booking;

use Livewire\Component;
use Livewire\Attributes\Modelable;

class Step3 extends Component
{
    #[Modelable]
    public string $name = '';

    #[Modelable]
    public string $email = '';

    #[Modelable]
    public string $phone = '';

    #[Modelable]
    public string $notes = '';

    public function render()
    {
        return view('livewire.business.booking.step3');
    }
}

<?php

/**
 * DEPRECATED - Booking module temporarily disabled
 * This component is part of the booking system that is not being developed in the current iteration.
 * DO NOT USE - Routes for this component are commented out in routes/web.php
 */

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

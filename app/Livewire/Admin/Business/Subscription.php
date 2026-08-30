<?php

namespace App\Livewire\Admin\Business;

use App\Models\Business;
use Livewire\Component;

class Subscription extends Component
{
    public Business $business;

    public function mount(Business $business)
    {
        $this->business = $business;
    }

    public function render()
    {
        return view('livewire.admin.business.subscription')
            ->layout('layouts.admin', ['business' => $this->business]);
    }
}

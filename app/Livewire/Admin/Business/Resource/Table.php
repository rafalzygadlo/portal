<?php

namespace App\Livewire\Admin\Business\Resource;

use App\Models\Business;
use Livewire\Component;

class Table extends Component
{


    public Business $business;

     protected $listeners = 
    [
        'resourceCreated' => '$refresh',
    ];

    public function mount($business)
    {
        $this->business = $business;
    }

    public function render()
    {   
        return view('livewire.admin.business.resource.index', [
            'resources' => $this->business->resources()->get(),
        ])->layout('layouts.admin', ['business' => $this->business]);
    }
}

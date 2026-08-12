<?php

namespace App\Livewire\Admin\Business\Service;

use App\Models\Business;
use Livewire\Component;

class Index extends Component
{

    public Business $business;
    
    protected $listeners = 
    [
        'serviceCreated' => '$refresh',
    ];

    public function mount($business)
    {
        $this->business = $business;
    }
    
    public function render()
    {

        return view('livewire.admin.business.service.index', [
            'services' => $this->business->services()->get(),
        ])->layout('layouts.admin', ['business' => $this->business]);
    }
}

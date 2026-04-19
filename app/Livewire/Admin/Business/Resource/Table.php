<?php

namespace App\Livewire\Admin\Business\Resource;

use App\Models\Business;
use App\Traits\ResolvesCurrentBusiness;
use Livewire\Component;

class Table extends Component
{
    use ResolvesCurrentBusiness;

    public Business $business;

     protected $listeners = 
    [
        'resourceCreated' => '$refresh',
    ];

    public function mount()
    {
        $this->business = $this->resolveCurrentBusiness();
    }

    public function render()
        return view('livewire.admin.business.resource.index', [
            'resources' => $this->business->resources()->get(),
        ])->layout('layouts.admin', ['business' => $this->business]);
    }
}

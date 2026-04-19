<?php

namespace App\Livewire\Admin\Business\Service;

use App\Models\Business;
use App\Traits\ResolvesCurrentBusiness;
use App\Traits\WithLivewireSorting;
use Livewire\Component;

class Index extends Component
{
    use ResolvesCurrentBusiness;

    public Business $business;
    
    protected $listeners = 
    [
        'serviceCreated' => '$refresh',
    ];

    public function mount()
    {
        $this->business = $this->resolveCurrentBusiness();
    }
    
    public function render()
    {

        return view('livewire.admin.business.service.index', [
            'services' => $this->business->services()->get(),
        ])->layout('layouts.admin', ['business' => $this->business]);
    }
}

<?php

namespace App\Livewire\Admin\Business\Resource;

use App\Models\Business;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class Index extends Component
{
    use AuthorizesRequests;

    public Business $business;
    protected $listeners = 
    [
        'resourceCreated' => '$refresh',
    ];

    public function mount($business)
    {
        $this->authorize('update', $business);
        $this->business = $business;
    }

    public function render()
    {
        return view('livewire.admin.business.resource.index', [
            'resources' => $this->business->resources()->get(),
        ])->layout('layouts.admin', ['business' => $this->business]);
    }
}

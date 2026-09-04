<?php

namespace App\Livewire\Admin\Company\Resource;

use App\Models\Company;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class Index extends Component
{
    use AuthorizesRequests;

    public Company $company;
    protected $listeners = 
    [
        'resourceCreated' => '$refresh',
    ];

    public function mount($company)
    {
        $this->authorize('update', $company);
        $this->company = $company;
    }

    public function render()
    {
        $resourcesGrouped = $this->company->resources()
            ->with('assignedUser')
            ->get()
            ->groupBy('type');

        return view('livewire.admin.company.resource.index', [
            'resourcesGrouped' => $resourcesGrouped,
        ])->layout('layouts.admin', ['company' => $this->company]);
    }
}

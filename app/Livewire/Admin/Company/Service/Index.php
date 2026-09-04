<?php

namespace App\Livewire\Admin\Company\Service;

use App\Models\Company;
use Livewire\Component;

class Index extends Component
{
    public Company $company;
    public bool $showDeleted = false;

    protected $listeners = [
        'serviceCreated' => '$refresh',
    ];

    public function mount($company)
    {
        $this->company = $company;
    }

    public function toggleDeletedView()
    {
        $this->showDeleted = ! $this->showDeleted;
    }

    public function toggleActive($serviceId)
    {
        $service = $this->company->services()->withoutTrashed()->find($serviceId);

        if (! $service) {
            session()->flash('error', 'Service not found.');
            return;
        }

        $service->is_active = ! $service->is_active;
        $service->save();

        session()->flash('success', 'Service status has been updated.');
    }
    public function delete($serviceId)
    {
        $service = $this->company->services()->withoutTrashed()->find($serviceId);

        if (! $service) {
            session()->flash('error', 'Service not found.');
            return;
        }

        $service->delete();
        session()->flash('success', 'Service has been deleted.');
    }

    public function restore($serviceId)
    {
        $service = $this->company->services()->withTrashed()->find($serviceId);

        if (! $service || ! $service->trashed()) {
            session()->flash('error', 'Deleted service not found.');
            return;
        }

        $service->restore();
        session()->flash('success', 'Service has been restored.');
    }

    public function forceDelete($serviceId)
    {
        $service = $this->company->services()->withTrashed()->find($serviceId);

        if (! $service) {
            session()->flash('error', 'Service not found.');
            return;
        }

        $service->forceDelete();
        session()->flash('success', 'Service has been permanently deleted.');
    }

    public function render()
    {
        $services = $this->showDeleted
            ? $this->company->services()->onlyTrashed()->latest()->get()
            : $this->company->services()->latest()->get();

        return view('livewire.admin.company.service.index', [
            'services' => $services,
        ])->layout('layouts.admin', ['company' => $this->company]);
    }
}

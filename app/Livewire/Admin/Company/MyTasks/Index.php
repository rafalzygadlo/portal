<?php

namespace App\Livewire\Admin\Company\MyTasks;

use App\Models\Company;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class Index extends Component
{
    use AuthorizesRequests;

    public Company $company;

    public function mount(Company $company): void
    {
        $this->authorize('manage', $company);
        $this->company = $company;
    }

    public function render()
    {
        $resourceIds = $this->company->resources()
            ->where('assigned_user_id', auth()->id())
            ->pluck('id');

        $reservations = $this->company->reservations()
            ->whereIn('resource_id', $resourceIds)
            ->with(['service', 'resource'])
            ->where('status', '!=', 'cancelled')
            ->orderBy('start_time')
            ->get();

        $bookings = $this->company->resourceBookings()
            ->whereIn('resource_id', $resourceIds)
            ->with('resource')
            ->where('status', '!=', 'cancelled')
            ->orderBy('start_time')
            ->get();

        return view('livewire.admin.company.my-tasks.index', [
            'reservations' => $reservations,
            'bookings' => $bookings,
        ])->layout('layouts.admin', ['company' => $this->company]);
    }
}
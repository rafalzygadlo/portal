<?php

namespace App\Livewire\Admin\Company\MyTasks;

use App\Models\Company;
use App\Models\CompanyUser;
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
        $companyUser = CompanyUser::where('company_id', $this->company->id)
            ->where('user_id', auth()->id())
            ->first();

        $reservations = $companyUser
            ? $this->company->reservations()
                ->where('company_user_id', $companyUser->id)
                ->with(['service', 'companyUser.user'])
                ->where('status', '!=', 'cancelled')
                ->orderBy('start_time')
                ->get()
            : collect();

        $resourceIds = $this->company->resources()
            ->where('assigned_user_id', auth()->id())
            ->pluck('id');

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
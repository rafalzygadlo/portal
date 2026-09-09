<?php

namespace App\Livewire\Admin\Company\Service;

use App\Models\Company;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Assign extends Component
{
    public Company $company;

    public bool $open = false;
    public ?int $serviceId = null;
    public array $userIds = [];

    protected $listeners = [
        'openAssign',
    ];

    public function mount()
    {
        $this->open = false;
        $this->serviceId = null;
        $this->userIds = [];
    }

    public function openAssign(int $serviceId): void
    {
        $service = $this->company->services()->withoutTrashed()->findOrFail($serviceId);

        $this->serviceId = $serviceId;
        $this->userIds = $service->companyUsers()->pluck('company_user.user_id')->map(fn ($id) => (string) $id)->all();
        $this->open = true;
    }

    public function close(): void
    {
        $this->open = false;
        $this->reset('serviceId', 'userIds');
    }

    public function save(): void
    {
        $service = $this->company->services()->withoutTrashed()->findOrFail($this->serviceId);

        $companyUserIds = DB::table('company_user')
            ->where('company_id', $this->company->id)
            ->whereIn('user_id', $this->userIds)
            ->pluck('id')
            ->all();

        $service->companyUsers()->sync($companyUserIds);

        $this->close();
        session()->flash('success', 'Service assignments have been updated.');
    }

    public function render()
    {
        return view('livewire.admin.company.service.assign', [
            'people' => $this->company->users()->orderBy('first_name')->orderBy('last_name')->get(),
        ]);
    }
}
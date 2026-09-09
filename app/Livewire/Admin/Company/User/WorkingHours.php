<?php

namespace App\Livewire\Admin\Company\User;

use App\Models\Company;
use App\Models\CompanyUser;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class WorkingHours extends Component
{
    use AuthorizesRequests;

    public Company $company;

    public bool $open = false;
    public ?int $userId = null;
    public array $workingHours = [];

    protected $listeners = [
        'openWorkingHours',
    ];

    public function mount(): void
    {
        $this->open = false;
        $this->userId = null;
        $this->workingHours = [];
    }

    public function openWorkingHours(int $userId): void
    {
        $this->authorize('manage', $this->company);

        $companyUser = $this->companyUser($userId);
        $this->userId = $userId;
        $this->workingHours = $companyUser->getWorkingHours();
        $this->open = true;
    }

    public function close(): void
    {
        $this->open = false;
        $this->reset('userId', 'workingHours');
    }

    public function save(): void
    {
        $this->authorize('manage', $this->company);

        $this->validate([
            'workingHours.*.open' => 'nullable|date_format:H:i',
            'workingHours.*.close' => 'nullable|date_format:H:i',
            'workingHours.*.closed' => 'boolean',
        ]);

        $companyUser = $this->companyUser($this->userId);
        $companyUser->update([
            'working_hours' => $this->workingHours,
        ]);

        $this->close();
        session()->flash('success', 'Working hours have been updated.');
    }

    private function companyUser(int $userId): CompanyUser
    {
        return CompanyUser::where('company_id', $this->company->id)
            ->where('user_id', $userId)
            ->firstOrFail();
    }

    public function render()
    {
        return view('livewire.admin.company.user.working-hours');
    }
}
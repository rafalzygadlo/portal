<?php

namespace App\Livewire\Admin\Company\User;

use App\Models\Company;
use App\Models\CompanyUser;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class FreeDays extends Component
{
    use AuthorizesRequests;

    public Company $company;

    public bool $open = false;
    public ?int $userId = null;
    public array $unavailablePeriods = [];
    public string $timeOffStart = '';
    public string $timeOffEnd = '';

    protected $listeners = [
        'openFreeDays',
    ];

    public function mount(): void
    {
        $this->open = false;
        $this->userId = null;
        $this->unavailablePeriods = [];
        $this->timeOffStart = '';
        $this->timeOffEnd = '';
    }

    public function openFreeDays(int $userId): void
    {
        $this->authorize('manage', $this->company);

        $companyUser = $this->companyUser($userId);
        $this->userId = $userId;
        $this->unavailablePeriods = $companyUser->unavailable_periods ?? [];
        $this->timeOffStart = '';
        $this->timeOffEnd = '';
        $this->open = true;
    }

    public function close(): void
    {
        $this->open = false;
        $this->reset('userId', 'unavailablePeriods', 'timeOffStart', 'timeOffEnd');
    }

    public function removePeriod(int $index): void
    {
        unset($this->unavailablePeriods[$index]);
        $this->unavailablePeriods = array_values($this->unavailablePeriods);
    }

    public function save(): void
    {
        $this->authorize('manage', $this->company);

        $this->validate([
            'timeOffStart' => 'nullable|date_format:Y-m-d',
            'timeOffEnd' => 'nullable|date_format:Y-m-d|after_or_equal:timeOffStart',
        ]);

        if (($this->timeOffStart && !$this->timeOffEnd) || (!$this->timeOffStart && $this->timeOffEnd)) {
            $this->addError('timeOffEnd', 'Set both dates for a time-off period.');
            return;
        }

        if ($this->timeOffStart && $this->timeOffEnd) {
            $this->unavailablePeriods[] = ['start' => $this->timeOffStart, 'end' => $this->timeOffEnd];
        }

        $companyUser = $this->companyUser($this->userId);
        $companyUser->update([
            'unavailable_periods' => $this->unavailablePeriods,
        ]);

        $this->close();
        session()->flash('success', 'Free days have been updated.');
    }

    private function companyUser(int $userId): CompanyUser
    {
        return CompanyUser::where('company_id', $this->company->id)
            ->where('user_id', $userId)
            ->firstOrFail();
    }

    public function render()
    {
        return view('livewire.admin.company.user.free-days');
    }
}
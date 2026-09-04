<?php

namespace App\Livewire\Admin\Company;

use App\Models\Company;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class WorkingHours extends Component
{
    use AuthorizesRequests;

    public Company $company;
    public array $workingHours = [];

    public function mount(Company $company): void
    {
        $this->authorize('update', $company);

        $this->company = $company;
        $this->workingHours = $company->getCompanyHours();
    }

    public function save(): void
    {
        $this->authorize('update', $this->company);

        $this->validate([
            'workingHours.*.open' => 'nullable|date_format:H:i',
            'workingHours.*.close' => 'nullable|date_format:H:i',
            'workingHours.*.closed' => 'boolean',
        ]);

        foreach ($this->workingHours as $day => $hours) {
            if ($hours['closed'] ?? false) {
                continue;
            }

            if (empty($hours['open']) || empty($hours['close'])) {
                $this->addError("workingHours.$day.open", 'Set opening and closing time, or mark the day as closed.');
                continue;
            }

            if (Carbon::createFromFormat('H:i', $hours['open']) >= Carbon::createFromFormat('H:i', $hours['close'])) {
                $this->addError("workingHours.$day.close", 'Closing time must be later than opening time.');
            }
        }

        if ($this->getErrorBag()->isNotEmpty()) {
            return;
        }

        $this->company->update(['company_hours' => $this->workingHours]);

        session()->flash('success', 'Working hours have been updated.');
    }

    public function render()
    {
        return view('livewire.admin.company.working-hours')
            ->layout('layouts.admin', ['company' => $this->company]);
    }
}
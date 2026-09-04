<?php

namespace App\Livewire\Admin\Business;

use App\Models\Business;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class WorkingHours extends Component
{
    use AuthorizesRequests;

    public Business $business;
    public array $workingHours = [];

    public function mount(Business $business): void
    {
        $this->authorize('update', $business);

        $this->business = $business;
        $this->workingHours = $business->getBusinessHours();
    }

    public function save(): void
    {
        $this->authorize('update', $this->business);

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

        $this->business->update(['business_hours' => $this->workingHours]);

        session()->flash('success', 'Working hours have been updated.');
    }

    public function render()
    {
        return view('livewire.admin.business.working-hours')
            ->layout('layouts.admin', ['business' => $this->business]);
    }
}
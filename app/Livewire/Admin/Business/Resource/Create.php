<?php

namespace App\Livewire\Admin\Business\Resource;

use App\Models\Business;
use App\Models\Resource;
use Livewire\Component;

class Create extends Component
{
    public Business $business;
    public string $name = '';
    public string $type = 'person';
    public string $hourlyRate = '';
    public ?Resource $editingResource = null;
    public array $workingHours = [];
    public array $unavailablePeriods = [];
    public string $timeOffStart = '';
    public string $timeOffEnd = '';
    public bool $open = false;

    protected $listeners = ['open'];

    public function open($id = null): void
    {
        $this->open = true;

        if ($id) {
            $this->editingResource = $this->business->resources()->whereKey($id)->firstOrFail();
            $this->name = $this->editingResource->name;
            $this->type = $this->editingResource->type;
            $this->hourlyRate = (string) ($this->editingResource->hourly_rate ?? '');
            $this->workingHours = $this->editingResource->getWorkingHours();
            $this->unavailablePeriods = $this->editingResource->unavailable_periods ?? [];
            return;
        }

        $this->editingResource = null;
        $this->workingHours = $this->business->getBusinessHours();
        $this->unavailablePeriods = [];
    }

    public function close(): void
    {
        $this->open = false;
        $this->reset('name', 'type', 'hourlyRate', 'editingResource', 'workingHours', 'unavailablePeriods', 'timeOffStart', 'timeOffEnd');
    }

    public function removeUnavailablePeriod(int $index): void
    {
        unset($this->unavailablePeriods[$index]);
        $this->unavailablePeriods = array_values($this->unavailablePeriods);
    }

    public function save(): void
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:person,facility,equipment',
            'hourlyRate' => 'nullable|numeric|min:0',
            'workingHours.*.open' => 'nullable|date_format:H:i',
            'workingHours.*.close' => 'nullable|date_format:H:i',
            'workingHours.*.closed' => 'boolean',
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

        $wasEditing = (bool) $this->editingResource;
        $attributes = [
            'name' => $this->name,
            'type' => $this->type,
            'hourly_rate' => $this->type === 'equipment' && $this->hourlyRate !== '' ? $this->hourlyRate : null,
            'working_hours' => $this->workingHours,
            'unavailable_periods' => $this->unavailablePeriods,
        ];

        if ($wasEditing) {
            $this->authorize('update', $this->editingResource);
            $this->editingResource->update($attributes);
        } else {
            $this->business->resources()->create($attributes);
        }

        session()->flash('success', 'Resource has been ' . ($wasEditing ? 'updated' : 'added') . '.');
        $this->close();
        $this->dispatch('resourceCreated');
    }

    public function render()
    {
        return view('livewire.admin.business.resource.create', [
            'open' => $this->open,
        ]);
    }
}

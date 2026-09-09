<?php

namespace App\Livewire\Admin\Company\Resource;

use App\Models\Company;
use App\Models\Resource;
use Livewire\Component;

class Create extends Component
{
    public Company $company;
    public string $name = '';
    public string $type = 'person';
    public string $hourlyRate = '';
    public ?Resource $editingResource = null;
    public bool $open = false;

    protected $listeners = ['open'];

    public function open($id = null): void
    {
        $this->open = true;

        if ($id) {
            $this->editingResource = $this->company->resources()->whereKey($id)->firstOrFail();
            $this->name = $this->editingResource->name;
            $this->type = $this->editingResource->type;
            $this->hourlyRate = (string) ($this->editingResource->hourly_rate ?? '');
            return;
        }

        $this->editingResource = null;
    }

    public function close(): void
    {
        $this->open = false;
        $this->reset('name', 'type', 'hourlyRate', 'editingResource');
    }

    public function save(): void
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:person,facility,equipment',
            'hourlyRate' => 'nullable|numeric|min:0',
        ]);

        $wasEditing = (bool) $this->editingResource;
        $attributes = [
            'name' => $this->name,
            'type' => $this->type,
            'hourly_rate' => $this->type === 'equipment' && $this->hourlyRate !== '' ? $this->hourlyRate : null,
        ];

        if ($wasEditing) {
            $this->authorize('update', $this->editingResource);
            $this->editingResource->update($attributes);
        } else {
            $this->company->resources()->create($attributes);
        }

        session()->flash('success', 'Resource has been ' . ($wasEditing ? 'updated' : 'added') . '.');
        $this->close();
        $this->dispatch('resourceCreated');
    }

    public function render()
    {
        return view('livewire.admin.company.resource.create', [
            'open' => $this->open,
        ]);
    }
}

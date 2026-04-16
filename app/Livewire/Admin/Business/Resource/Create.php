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

    public bool $open = false;
    
    protected $listeners = 
    [
        'openResourceModal',
        'closeResourceModal', 
        'saveResource'
    ];

    public function mount(Business $business)
    {
        $this->business = $business;
    }

    public function openResourceModal()
    {
        $this->open = true;
    }

    public function closeResourceModal()
    {
        $this->open = false;
        $this->reset('name', 'type');
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:person,facility,equipment'
        ]);

        $this->business->resources()->create([
            'name' => $this->name,
            'type' => $this->type
        ]);

        session()->flash('success', 'Resource has been added.');
        $this->closeResourceModal();
        $this->dispatch('resourceCreated');

    }

    public function render()
    {
        return view('livewire.admin.business.resource.create', [
            'open' => $this->open])
        ->layout('layouts.admin', ['business' => $this->business]);
    }
}

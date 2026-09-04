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
        'open',
    ];

    public function open($id = null)
    {
        $this->open = true;
    }

    public function close()
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
        $this->close();
        $this->dispatch('resourceCreated');

    }

    public function render()
    {
        return view('livewire.admin.business.resource.create', [
            'open' => $this->open]);
       
    }
}

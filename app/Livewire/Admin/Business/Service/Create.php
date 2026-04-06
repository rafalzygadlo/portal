<?php

namespace App\Livewire\Admin\Business\Service;

use App\Models\Business;
use App\Models\Service;
use Livewire\Component;

class Create extends Component
{
    public Business $business;
    
    //form fields
    public string $name = 'testowa usługa';
    public string $description = 'przykładowy opis usługi';
    public int $duration = 60;        
    public float $price = 100.00;
    public int $buffer = 15;
    
    public bool $open = false;
    public bool $editingService = false;
    protected $listeners = 
    [
        'openServiceModal',
        'closeServiceModal', 
        'saveService'
    ];
    
    public function mount(Business $business)
    {
        $this->business = $business;
    }

    public function closeServiceModal()
    {
        $this->open = false;
        $this->reset('name', 'description', 'duration', 'price', 'buffer', 'editingService');
    }
    
    public function openServiceModal()
    {
        $this->open = true;
    }

    public function saveService()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'duration' => 'required|integer|min:15|max:480',
            'price' => 'required|numeric|min:0',
            'buffer' => 'required|integer|min:0|max:120'
            
        ]);

        $this->business->services()->create([
            'name' => $this->name,
            'description' => $this->description,
            'duration' => $this->duration,
            'price' => $this->price,
            'buffer' => $this->buffer,
            'is_active' => true,
        ]);

        session()->flash('success', 'Zasób został dodany.');
        $this->closeServiceModal();
        $this->dispatch('serviceCreated');
    }

    public function render()
    {
        return view('livewire.admin.business.service.create', [
            'open' => $this->open,
        ])->layout('layouts.business', ['business' => $this->business]);
    }
}

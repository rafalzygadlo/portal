<?php

namespace App\Livewire\Admin\Business\Service;

use App\Models\Business;
use App\Models\Service;
use App\Traits\ResolvesCurrentBusiness;
use Livewire\Component;

class Create extends Component
{
    use ResolvesCurrentBusiness;

    public Business $business;
    
    //form fields
    public string $name = 'test service';
    public string $description = 'sample service description';
    public int $duration = 60;        
    public float $price = 100.00;
    public int $buffer = 15;
    
    public bool $open = false;
    public ?Service $editingService = null;
    protected $listeners = 
    [
        'openServiceModal',
        'closeServiceModal', 
        'saveService'
    ];
    
    public function mount()
    {
        $this->business = $this->resolveCurrentBusiness();
        $this->editingService = null;
    }

    public function closeServiceModal()
    {
        $this->open = false;
        $this->reset('name', 'description', 'duration', 'price', 'buffer', 'editingService');
    }
    
    public function openServiceModal($serviceId = null)
    {
        $this->open = true;

        if ($serviceId) {
            $serviceModel = Service::find($serviceId);
        } else {
            $serviceModel = null;
        }

        if ($serviceModel) {
            $this->editingService = $serviceModel;
            $this->name = $serviceModel->name;
            $this->description = $serviceModel->description;
            $this->duration = $serviceModel->duration;
            $this->price = $serviceModel->price;
            $this->buffer = $serviceModel->buffer;
        } else {
            $this->editingService = null;
            $this->reset('name', 'description', 'duration', 'price', 'buffer');
        }
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

        if ($this->editingService) 
        {
            $this->editingService->update([
                'name' => $this->name,
                'description' => $this->description,
                'price' => $this->price ?: null,
                'duration' => $this->duration,
                'buffer' => $this->buffer,
            ]);
        } 
        else 
        {
             $this->business->services()->create([
                'name' => $this->name,
                'description' => $this->description,
                'duration' => $this->duration,
                'price' => $this->price,
                'buffer' => $this->buffer,
                'is_active' => true,
            ]);
        }

        session()->flash('success', 'Resource has been added.');
        $this->closeServiceModal();
        $this->dispatch('serviceCreated');
    }

    public function render()
    {
        return view('livewire.admin.business.service.create', [
            'open' => $this->open,
        ])->layout('layouts.admin', ['business' => $this->business]);
    }
}

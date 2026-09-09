<?php

namespace App\Livewire\Admin\Company\Service;

use App\Models\Company;
use App\Models\Service;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Create extends Component
{

    public Company $company;
    
    //form fields
    public string $name = 'test service';
    public string $description = 'sample service description';
    public int $duration = 60;        
    public float $price = 100.00;
    public int $buffer = 15;
    public array $userIds = [];
    
    public bool $open = false;
    public ?Service $editingService = null;
    protected $listeners = 
    [
        'open',
    ];
    
    public function mount()
    {
        $this->editingService = null;
    }

    public function close()
    {
        $this->open = false;
        $this->reset('name', 'description', 'duration', 'price', 'buffer', 'userIds', 'editingService');
    }
    
    public function open($serviceId = null)
    {
    
        $this->open = true;

        if ($serviceId) 
        {
            $serviceModel = Service::find($serviceId);
        } else {
            $serviceModel = null;
        }

        if ($serviceModel) 
        {
            $this->editingService = $serviceModel;
            $this->name = $serviceModel->name;
            $this->description = $serviceModel->description;
            $this->duration = $serviceModel->duration;
            $this->price = $serviceModel->price;
            $this->buffer = $serviceModel->buffer;
            $this->userIds = $serviceModel->companyUsers()->pluck('company_user.user_id')->map(fn ($id) => (string) $id)->all();
        } 
        else 
        {
            $this->editingService = null;
            $this->reset('name', 'description', 'duration', 'price', 'buffer', 'userIds');
        }
    }

    public function toggleServiceActive($serviceId)
    {
        $service = Service::find($serviceId);
        if ($service) 
        {
            $service->is_active = !$service->is_active;
            $service->save();
            session()->flash('success', 'Service status updated.');
        } 
        else 
        {
            session()->flash('error', 'Service not found.');
        }
    }

    public function save()
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
            $this->authorize('update', $this->editingService);
            $this->editingService->update([
                'name' => $this->name,
                'description' => $this->description,
                'price' => $this->price ?: null,
                'duration' => $this->duration,
                'buffer' => $this->buffer,
            ]);
            $this->editingService->companyUsers()->sync($this->companyUserIdsFor($this->userIds));
        } 
        else 
        {
            
            $service = $this->company->services()->create([
                'name' => $this->name,
                'description' => $this->description,
                'duration' => $this->duration,
                'price' => $this->price,
                'buffer' => $this->buffer,
                'is_active' => true,
            ]);
            $service->companyUsers()->sync($this->companyUserIdsFor($this->userIds));
        }

        $this->close();
        $this->dispatch('serviceCreated');
    }

    private function companyUserIdsFor(array $userIds): array
    {
        return DB::table('company_user')
            ->where('company_id', $this->company->id)
            ->whereIn('user_id', $userIds)
            ->pluck('id')
            ->all();
    }

    public function render()
    {   
        return  view('livewire.admin.company.service.create', [
            'open' => $this->open,
            'people' => $this->company->users()->orderBy('first_name')->orderBy('last_name')->get(),
        ]);

    }
}

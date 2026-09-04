<?php

namespace App\Livewire\Admin\Company;

use App\Models\Company;
use App\Models\Service;
use App\Models\Reservation;
use Livewire\Component;
use Livewire\WithPagination;
use Carbon\Carbon;
use Livewire\Attributes\Url;

class Dashboard extends Component
{
    use WithPagination;

    public Company $company;
    public bool $showServiceModal = false;
    public string $serviceName = '';
    public string $serviceDescription = '';
    public string $servicePrice = '';
    public string $serviceDuration = '30';
    public string $serviceBuffer = '15';
    public ?Reservation $editingService = null;

    public function mount($company)
    {
        $this->company = $company;
    }

    public function openServiceModal(?Reservation $service = null)
    {
        if ($service) {
            $this->editingService = $service;
            $this->serviceName = $service->name;
            $this->serviceDescription = $service->description;
            $this->servicePrice = $service->price;
            $this->serviceDuration = $service->duration;
            $this->serviceBuffer = $service->buffer;
        } else {
            $this->reset('serviceName', 'serviceDescription', 'servicePrice', 'serviceDuration', 'serviceBuffer', 'editingService');
            $this->serviceDuration = '30';
            $this->serviceBuffer = '15';
        }
        
        $this->showServiceModal = true;
    }

    public function closeServiceModal()
    {
        $this->showServiceModal = false;
        $this->reset('serviceName', 'serviceDescription', 'servicePrice', 'serviceDuration', 'serviceBuffer', 'editingService');
    }

    public function saveService()
    {
        $this->validate([
            'serviceName' => 'required|string|max:255',
            'serviceDuration' => 'required|numeric|min:15|max:480',
            'serviceBuffer' => 'required|numeric|min:0|max:120',
            'servicePrice' => 'nullable|numeric|min:0',
        ]);

        if ($this->editingService) {
            $this->editingService->update([
                'name' => $this->serviceName,
                'description' => $this->serviceDescription,
                'price' => $this->servicePrice ?: null,
                'duration' => $this->serviceDuration,
                'buffer' => $this->serviceBuffer,
            ]);
        } else {
            Service::create([
                'company_id' => $this->company->id,
                'name' => $this->serviceName,
                'description' => $this->serviceDescription,
                'price' => $this->servicePrice ?: null,
                'duration' => $this->serviceDuration,
                'buffer' => $this->serviceBuffer,
                'is_active' => true,
            ]);
        }

        $this->closeServiceModal();
        $this->company->refresh();
        session()->flash('success', 'Service has been ' . ($this->editingService ? 'updated' : 'added') . '!');
    }

    public function deleteService(Service $service)
    {
        $this->authorize('update', $this->company);
        $service->delete();
        $this->company->refresh();
        session()->flash('success', 'Service has been deleted.');
    }

    public function toggleServiceActive(Service $service)
    {
        $this->authorize('update', $this->company);
        $service->update(['is_active' => !$service->is_active]);
        $this->company->refresh();
    }

    public function confirmReservation(Reservation $reservation)
    {
        $this->authorize('update', $this->company);
        $reservation->update(['status' => 'confirmed']);
        session()->flash('success', 'Rezerwacja potwierdzona!');
    }

    public function cancelReservation(Reservation $reservation)
    {
        $this->authorize('update', $this->company);
        $reservation->update(['status' => 'cancelled']);
        session()->flash('success', 'Rezerwacja anulowana.');
    }

    public function render()
    {
        /*
        $reservations = $this->company->reservations()
            ->with('service', 'user')
            ->latest()
            ->paginate(10);
        */
        $reservations = null;    

        return view('livewire.admin.company.dashboard', [
            'services' => $this->company->services()->get(),
            'reservations' => $reservations,
        ])->layout('layouts.admin', ['company' => $this->company]);
    }
}

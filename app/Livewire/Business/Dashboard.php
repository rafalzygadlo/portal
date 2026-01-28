<?php

namespace App\Livewire\Business;

use App\Models\Business;
use App\Models\ReservationService;
use App\Models\Reservation;
use Livewire\Component;
use Livewire\WithPagination;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class Dashboard extends Component
{
    use WithPagination;

    public Business $business;
    public string $tab = 'services'; // services, reservations, settings
    public bool $showServiceModal = false;
    public string $serviceName = '';
    public string $serviceDescription = '';
    public string $servicePrice = '';
    public string $serviceDuration = '30';
    public string $serviceBuffer = '15';
    public ?ReservationService $editingService = null;

    public function mount(Business $business)
    {
        $this->authorize('update', $business);
        $this->business = $business;
    }

    public function setTab(string $tab)
    {
        $this->tab = $tab;
    }

    public function openServiceModal(?ReservationService $service = null)
    {
        if ($service) {
            $this->editingService = $service;
            $this->serviceName = $service->name;
            $this->serviceDescription = $service->description;
            $this->servicePrice = $service->price;
            $this->serviceDuration = $service->duration_minutes;
            $this->serviceBuffer = $service->buffer_minutes;
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
                'duration_minutes' => $this->serviceDuration,
                'buffer_minutes' => $this->serviceBuffer,
            ]);
        } else {
            ReservationService::create([
                'business_id' => $this->business->id,
                'name' => $this->serviceName,
                'description' => $this->serviceDescription,
                'price' => $this->servicePrice ?: null,
                'duration_minutes' => $this->serviceDuration,
                'buffer_minutes' => $this->serviceBuffer,
                'is_active' => true,
            ]);
        }

        $this->closeServiceModal();
        $this->business->refresh();
        session()->flash('success', 'Usługa została ' . ($this->editingService ? 'zaktualizowana' : 'dodana') . '!');
    }

    public function deleteService(ReservationService $service)
    {
        $this->authorize('update', $this->business);
        $service->delete();
        $this->business->refresh();
        session()->flash('success', 'Usługa została usunięta.');
    }

    public function toggleServiceActive(ReservationService $service)
    {
        $this->authorize('update', $this->business);
        $service->update(['is_active' => !$service->is_active]);
        $this->business->refresh();
    }

    public function confirmReservation(Reservation $reservation)
    {
        $this->authorize('update', $this->business);
        $reservation->update(['status' => 'confirmed']);
        session()->flash('success', 'Rezerwacja potwierdzona!');
    }

    public function cancelReservation(Reservation $reservation)
    {
        $this->authorize('update', $this->business);
        $reservation->update(['status' => 'cancelled']);
        session()->flash('success', 'Rezerwacja anulowana.');
    }

    public function render()
    {
        $reservations = $this->business->reservations()
            ->with('service', 'user')
            ->latest()
            ->paginate(10);

        return view('livewire.business.dashboard', [
            'services' => $this->business->services()->orderBy('sort_order')->get(),
            'reservations' => $reservations,
        ])->layout('layouts.business', ['business' => $this->business]);
    }
}

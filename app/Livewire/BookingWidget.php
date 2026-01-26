<?php

namespace App\Livewire;

use App\Models\Business;
use App\Models\Reservation;
use App\Models\ReservationService;
use Livewire\Component;
use Livewire\Attributes\Computed;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class BookingWidget extends Component
{
    public Business $business;
    public string $selectedServiceId = '';
    public string $selectedDate = '';
    public string $selectedTime = '';
    public string $clientName = '';
    public string $clientEmail = '';
    public string $clientPhone = '';
    public string $notes = '';
    public array $weekDays = [];
    public array $weekSlots = [];
    public Collection $services;
    public Carbon $weekStart;

    #[Computed]
    public function selectedService()
    {
        return $this->selectedServiceId ? ReservationService::find($this->selectedServiceId) : null;
    }

    public function mount(Business $business)
    {
        $this->business = $business;
        $this->services = $business->services()->where('is_active', true)->get();
        $this->weekStart = now()->addDay()->startOfWeek();
        $this->selectedDate = $this->weekStart->format('Y-m-d');
        $this->loadWeekSlots();
    }

    public function updatedSelectedDate($value)
    {
        $this->selectedDate = $value;
        $this->availableSlots = [];
        $this->selectedTime = '';
        
        if ($this->selectedService) {
            $this->loadAvailableSlots();
        }
    }

    public function updatedSelectedServiceId($serviceId)
    {
        $this->selectedTime = '';
        $this->loadWeekSlots();
    }

    public function nextWeek()
    {
        $this->weekStart = $this->weekStart->addWeek()->startOfWeek();
        $this->selectedDate = $this->weekStart->format('Y-m-d');
        $this->loadWeekSlots();
    }

    public function previousWeek()
    {
        $this->weekStart = $this->weekStart->subWeek()->startOfWeek();
        $this->selectedDate = $this->weekStart->format('Y-m-d');
        $this->loadWeekSlots();
    }

    protected function loadWeekSlots(): void
    {
        if (!$this->selectedService()) {
            return;
        }

        $this->weekDays = [];
        $this->weekSlots = [];

        $businessHours = $this->business->getBusinessHours();
        $dayMap = ['mon' => 'Monday', 'tue' => 'Tuesday', 'wed' => 'Wednesday', 'thu' => 'Thursday', 'fri' => 'Friday', 'sat' => 'Saturday', 'sun' => 'Sunday'];

        for ($i = 0; $i < 7; $i++) {
            $date = $this->weekStart->copy()->addDays($i);
            $dayKey = strtolower(substr($date->format('D'), 0, 3));
            $dayName = $dayMap[$dayKey] ?? null;

            $this->weekDays[$date->format('Y-m-d')] = [
                'date' => $date,
                'dayName' => $dayName,
                'formatted' => $date->format('d.m'),
            ];

            // Sprawdzenie czy biznes jest otwarty
            if (!$dayKey || ($businessHours[$dayKey]['closed'] ?? false)) {
                $this->weekSlots[$date->format('Y-m-d')] = [];
                continue;
            }

            $slots = [];
            $openTime = Carbon::parse($businessHours[$dayKey]['open']);
            $closeTime = Carbon::parse($businessHours[$dayKey]['close']);
            $slotDuration = $this->business->booking_slot_duration ?? 30;
            $serviceDuration = $this->selectedService()->duration_minutes;
            $buffer = $this->selectedService()->buffer_minutes ?? 0;

            $current = $date->copy()->setHour($openTime->hour)->setMinute($openTime->minute)->second(0);
            $dayClose = $date->copy()->setHour($closeTime->hour)->setMinute($closeTime->minute)->second(0);

            while ($current->copy()->addMinutes($serviceDuration) <= $dayClose) {
                $slotEnd = $current->copy()->addMinutes($serviceDuration);
                $isAvailable = Reservation::isTimeSlotAvailable(
                    $this->business->id,
                    $this->selectedService()->id,
                    $current->format('Y-m-d H:i:s'),
                    $slotEnd->format('Y-m-d H:i:s')
                );

                $slots[] = [
                    'time' => $current->format('H:i'),
                    'endTime' => $slotEnd->format('H:i'),
                    'available' => $isAvailable,
                    'fullDateTime' => $current->format('Y-m-d H:i:s'),
                ];

                $current->addMinutes($slotDuration);
            }

            $this->weekSlots[$date->format('Y-m-d')] = $slots;
        }
    }

    public function selectSlot($dateTime)
    {
        $dateTimeParts = explode(' ', $dateTime);
        $this->selectedDate = $dateTimeParts[0];
        $this->selectedTime = $dateTimeParts[1];
    }

    public function book()
    {
        $this->validate([
            'selectedService' => 'required',
            'selectedDate' => 'required|date',
            'selectedTime' => 'required',
            'clientName' => 'required|string',
            'clientEmail' => 'required|email',
        ]);

        $startTime = Carbon::parse($this->selectedDate . ' ' . $this->selectedTime);
        $service = $this->selectedService();
        
        // Sprawdzenie dostępności
        if (!Reservation::isTimeSlotAvailable(
            $this->business->id,
            $service->id,
            $startTime->format('Y-m-d H:i:s'),
            $startTime->copy()->addMinutes($service->duration_minutes)->format('Y-m-d H:i:s')
        )) {
            $this->addError('selectedTime', 'Wybrany termin jest już zajęty.');
            $this->dispatch('notify', message: 'Wybrany termin jest już zajęty.', type: 'error');
            return;
        }

        Reservation::create([
            'business_id' => $this->business->id,
            'reservation_service_id' => $service->id,
            'user_id' => Auth::id(),
            'client_name' => $this->clientName,
            'client_email' => $this->clientEmail,
            'client_phone' => $this->clientPhone,
            'start_time' => $startTime,
            'end_time' => $startTime->copy()->addMinutes($service->duration_minutes),
            'notes' => $this->notes,
            'status' => 'pending',
        ]);

        session()->flash('success', 'Rezerwacja została złożona! Czekamy na potwierdzenie.');
        $this->reset();
        $this->mount($this->business);
    }

    public function render()
    {
        return view('livewire.booking-widget', [
            'services' => $this->business->services()->where('is_active', true)->get(),
            'selectedService' => $this->selectedService,
        ]);
    }
}

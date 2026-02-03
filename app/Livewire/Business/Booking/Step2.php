<?php

namespace App\Livewire\Business\Booking;

use App\Models\Business;
use App\Models\Reservation;
use App\Models\ReservationService;
use Livewire\Component;
use Livewire\Attributes\Computed;
use Carbon\Carbon;

class Step2 extends Component
{
    public Business $business;
    public string $selectedServiceId;
    public string $selectedDate = '';
    public string $selectedTime = '';
    public array $weekDays = [];
    public array $weekSlots = [];
    public Carbon $weekStart;

    #[Computed]
    public function selectedService()
    {
        return $this->selectedServiceId ? ReservationService::find($this->selectedServiceId) : null;
    }

    public function mount(Business $business, string $selectedServiceId, string $selectedDate, string $selectedTime)
    {
        $this->business = $business;
        $this->selectedServiceId = $selectedServiceId;
        $this->weekStart = now()->addDay()->startOfWeek();
        $this->selectedDate = $selectedDate ?: $this->weekStart->format('Y-m-d');
        $this->selectedTime = $selectedTime;
        $this->loadWeekSlots();
    }

    public function updatedSelectedDate($value)
    {
        $this->selectedDate = $value;
        $this->selectedTime = '';
        $this->dispatch('dateTimeSelected', $this->selectedDate, $this->selectedTime);
    }

    public function nextWeek()
    {
        $selectedDayOfWeek = Carbon::parse($this->selectedDate)->dayOfWeekIso;
        $this->weekStart = $this->weekStart->addWeek()->startOfWeek();
        $this->selectedDate = $this->weekStart->copy()->addDays($selectedDayOfWeek - 1)->format('Y-m-d');
        $this->loadWeekSlots();
    }

    public function previousWeek()
    {
        $newWeekStart = $this->weekStart->copy()->subWeek()->startOfWeek();

        if ($newWeekStart->lt(now()->addDay()->startOfWeek())) {
            return;
        }

        $selectedDayOfWeek = Carbon::parse($this->selectedDate)->dayOfWeekIso;
        $this->weekStart = $newWeekStart;
        $this->selectedDate = $this->weekStart->copy()->addDays($selectedDayOfWeek - 1)->format('Y-m-d');
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
            $isPast = $date->lt(now()->startOfDay());

            $this->weekDays[$date->format('Y-m-d')] = [
                'date' => $date,
                'dayName' => $dayName,
                'formatted' => $date->format('d.m'),
                'isPast' => $isPast,
            ];

            if ($isPast || !$dayKey || ($businessHours[$dayKey]['closed'] ?? false)) {
                $this->weekSlots[$date->format('Y-m-d')] = [];
                continue;
            }

            $slots = [];
            $openTime = Carbon::parse($businessHours[$dayKey]['open']);
            $closeTime = Carbon::parse($businessHours[$dayKey]['close']);
            $slotDuration = $this->business->booking_slot_duration ?? 30;
            $serviceDuration = $this->selectedService()->duration_minutes;

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

                if ($current->isPast()) {
                    $isAvailable = false;
                }

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
        $this->dispatch('dateTimeSelected', $this->selectedDate, $this->selectedTime);
    }

    public function render()
    {
        return view('livewire.business.booking.step2');
    }
}

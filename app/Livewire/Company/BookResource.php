<?php

namespace App\Livewire\Company;

use App\Models\Company;
use App\Models\Resource;
use App\Models\ResourceBooking;
use App\Traits\NotifiesCompanyUsers;
use Carbon\Carbon;
use Livewire\Component;

class BookResource extends Component
{
    use NotifiesCompanyUsers;
    public Company $company;
    public int $step = 1;
    public array $resourceIds = [];
    public string $durationHours = '1';
    public string $selectedDate = '';
    public string $startTime = '';
    public string $calendarMonth = '';

    public function mount(Company $company): void
    {
        $this->company = $company;
        $this->calendarMonth = now('Europe/Warsaw')->format('Y-m');

        if (auth()->check() && ($draft = session()->pull($this->draftKey()))) {
            $this->resourceIds = $draft['resource_ids'] ?? [$draft['resource_id']];
            $this->durationHours = $draft['duration_hours'];
            $this->startTime = $draft['start_time'];
            $this->step = 3;
        }
    }

    public function toggleResource(int $resourceId): void
    {
        $resource = $this->equipment()->whereKey($resourceId)->firstOrFail();

        if (in_array($resource->id, $this->resourceIds)) {
            $this->resourceIds = array_values(array_diff($this->resourceIds, [$resource->id]));
        } else {
            $this->resourceIds[] = $resource->id;
        }
        $this->resourceIds = array_map('intval', $this->resourceIds);
        $this->selectedDate = '';
        $this->startTime = '';
    }

    public function continueResources(): void
    {
        $this->validate(['resourceIds' => 'required|array|min:1', 'resourceIds.*' => 'integer']);
        $this->step = 2;
    }

    public function selectDate(string $date): void
    {
        $this->selectedDate = $date;
        $this->startTime = '';
    }

    public function selectTime(string $startTime): void
    {
        $this->startTime = $startTime;
    }

    public function previousMonth(): void
    {
        $current = Carbon::createFromFormat('Y-m', $this->calendarMonth, 'Europe/Warsaw')->startOfMonth();
        $previous = $current->copy()->subMonth();

        if ($previous->lt(now('Europe/Warsaw')->startOfMonth())) {
            return;
        }

        $this->calendarMonth = $previous->format('Y-m');
    }

    public function nextMonth(): void
    {
        $current = Carbon::createFromFormat('Y-m', $this->calendarMonth, 'Europe/Warsaw')->startOfMonth();
        $this->calendarMonth = $current->copy()->addMonth()->format('Y-m');
    }

    public function updatedDurationHours(): void
    {
        if ($this->startTime) {
            $this->startTime = '';
        }
    }

    public function continueBooking()
    {
        $this->validate([
            'resourceIds' => 'required|array|min:1',
            'durationHours' => 'required|integer|min:1|max:24',
            'startTime' => 'required|date_format:Y-m-d\\TH:i',
        ]);

        if ($this->equipment()->whereIn('id', $this->resourceIds)->count() !== count($this->resourceIds)) {
            $this->addError('resourceIds', 'Select available equipment.');
            return;
        }

        if (!$this->resourcesAreAvailable($this->startDate(), $this->endDate())) {
            $this->addError('startTime', 'This equipment is not available at the selected time.');
            return;
        }

        if (!auth()->check()) {
            session()->put($this->draftKey(), [
                'resource_ids' => $this->resourceIds,
                'duration_hours' => $this->durationHours,
                'start_time' => $this->startTime,
            ]);

            return redirect()->guest(route('login.subdomain', ['company' => $this->company]));
        }

        $this->step = 3;
    }

    public function confirmBooking(): void
    {
        if (!auth()->check()) {
            redirect()->guest(route('login.subdomain', ['company' => $this->company]));
            return;
        }

        $this->validate([
            'resourceIds' => 'required|array|min:1',
            'durationHours' => 'required|integer|min:1|max:24',
            'startTime' => 'required|date_format:Y-m-d\\TH:i',
        ]);

        $start = $this->startDate();
        $end = $this->endDate();

        if (!$this->resourcesAreAvailable($start, $end)) {
            $this->addError('startTime', 'This equipment was booked by someone else. Choose another time.');
            $this->step = 2;
            return;
        }

        $booking = ResourceBooking::create([
            'company_id' => $this->company->id,
            'resource_id' => $this->resourceIds[0],
            'resource_ids' => $this->resourceIds,
            'user_id' => auth()->id(),
            'client_name' => auth()->user()->name,
            'client_email' => auth()->user()->email,
            'start_time' => $start,
            'end_time' => $end,
            'status' => 'pending',
            'total_price' => $this->totalPrice(),
        ]);

        $resourceNames = Resource::whereIn('id', $this->resourceIds)->pluck('name')->implode(', ');

        $this->notifyCompanyUsers(
            'resource_booking',
            "Nowa rezerwacja sprzętu: {$resourceNames} od {$booking->client_name} ({$start->format('d.m.Y H:i')})",
            $booking->id,
            'ResourceBooking'
        );

        session()->forget($this->draftKey());
        session()->flash('success', 'Your booking request has been sent.');
        $this->step = 4;
    }

    public function render()
    {
        return view('livewire.company.resource-booking', [
            'equipment' => $this->equipment()->orderBy('name')->get(),
            'selectedResources' => $this->resourceIds ? Resource::whereIn('id', $this->resourceIds)->get() : collect(),
            'calendarDays' => $this->calendarDays(),
            'availableTimes' => $this->availableTimesForSelectedDate(),
        ])->layout('layouts.company', ['company' => $this->company]);
    }

    private function equipment()
    {
        return $this->company->resources()->where('type', 'equipment')->where('is_active', true);
    }

    private function draftKey(): string
    {
        return 'equipment_booking.' . $this->company->id;
    }

    private function startDate(): Carbon
    {
        return Carbon::createFromFormat('Y-m-d\\TH:i', $this->startTime, 'Europe/Warsaw');
    }

    private function endDate(): Carbon
    {
        return $this->startDate()->copy()->addHours((int) $this->durationHours);
    }

    private function isAvailable(int $resourceId, Carbon $start, Carbon $end): bool
    {
        return !ResourceBooking::where('resource_id', $resourceId)
            ->where('status', '!=', 'cancelled')
            ->where('start_time', '<', $end)
            ->where('end_time', '>', $start)
            ->exists();
    }

    private function resourcesAreAvailable(Carbon $start, Carbon $end): bool
    {
        foreach ($this->resourceIds as $resourceId) {
            if (!$this->isAvailable($resourceId, $start, $end)) {
                return false;
            }
        }
        return true;
    }

    private function totalPrice(): float
    {
        return (float) Resource::whereIn('id', $this->resourceIds)->sum('hourly_rate') * (int) $this->durationHours;
    }

    /**
     * Every day of the visible month, flagged as past/closed so the calendar can disable them.
     */
    private function calendarDays(): array
    {
        $month = Carbon::createFromFormat('Y-m', $this->calendarMonth, 'Europe/Warsaw')->startOfMonth();
        $today = now('Europe/Warsaw')->startOfDay();
        $hours = $this->company->getCompanyHours();
        $days = [];

        for ($date = $month->copy(); $date->month === $month->month; $date->addDay()) {
            $dayKey = strtolower($date->format('D'));

            $days[] = [
                'date' => $date->format('Y-m-d'),
                'day' => $date->day,
                'weekday' => $date->dayOfWeekIso,
                'isPast' => $date->lt($today),
                'isClosed' => $hours[$dayKey]['closed'] ?? false,
            ];
        }

        return $days;
    }

    /**
     * All free slots on the selected date, instead of just the next few sequential ones.
     */
    private function availableTimesForSelectedDate(): array
    {
        if (!$this->selectedDate || !$this->resourceIds || !$this->durationHours) {
            return [];
        }

        $date = Carbon::createFromFormat(
            'Y-m-d',
            $this->selectedDate,
            'Europe/Warsaw'
        )->startOfDay();

        $dayKey = strtolower($date->format('D'));

        $dayHours = $this->company->getCompanyHours()[$dayKey]
            ?? ['closed' => true];

        if ($dayHours['closed'] ?? false) {
            return [];
        }

        $openTime = Carbon::parse($dayHours['open'] ?? '00:00');
        $closeTime = Carbon::parse($dayHours['close'] ?? '00:00');

        $duration = (int) $this->durationHours;
        $now = now('Europe/Warsaw');

        $candidate = $date->copy()->setTime(
            $openTime->hour,
            $openTime->minute
        );

        $dayClose = $date->copy()->setTime(
            $closeTime->hour,
            $closeTime->minute
        );

        $times = [];

        // Godzina rozpoczęcia musi mieścić się w godzinach pracy.
        while ($candidate->lte($dayClose)) {

            // Nie pokazuj godzin, które już minęły.
            if ($candidate->gt($now)) {

                // Wypożyczenie może przechodzić przez północ.
                $end = $candidate->copy()->addHours($duration);

                if (
                    $this->resourcesAreAvailableAt(
                        $this->resourceIds,
                        $candidate,
                        $end
                    )
                ) {
                    $times[] = $candidate->format('Y-m-d\TH:i');
                }
            }

            $candidate->addMinutes(30);
        }

        return $times;
    }
    private function resourcesAreAvailableAt(array $resourceIds, Carbon $start, Carbon $end): bool
    {
        foreach ($resourceIds as $resourceId) {
            if (!$this->isAvailable($resourceId, $start, $end)) {
                return false;
            }
        }
        return true;
    }
}
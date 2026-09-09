<?php

namespace App\Livewire\Company;

use App\Models\Company;
use App\Models\CompanyUser;
use App\Models\Reservation;
use App\Traits\NotifiesCompanyUsers;
use Carbon\Carbon;
use Livewire\Component;

class BookService extends Component
{
    use NotifiesCompanyUsers;
    public Company $company;
    public int $step = 1;
    public string $serviceId = '';
    public array $serviceIds = [];
    public string $companyUserId = '';
    public string $selectedDate = '';
    public string $startTime = '';

    public function mount(Company $company): void
    {
        $this->company = $company;
        $this->selectedDate = $this->firstOpenDay()->format('Y-m-d');

        if (auth()->check() && ($draft = session()->pull($this->draftKey()))) {
            $this->serviceIds = $draft['service_ids'] ?? [$draft['service_id']];
            $this->serviceId = (string) $this->serviceIds[0];
            $this->companyUserId = $draft['company_user_id'];
            $this->startTime = $draft['start_time'];
            $this->selectedDate = substr($draft['start_time'], 0, 10);
            $this->step = 3;
        }
    }

    public function toggleService(int $serviceId): void
    {
        $this->services()->whereKey($serviceId)->firstOrFail();
        if (in_array($serviceId, $this->serviceIds)) {
            $this->serviceIds = array_values(array_diff($this->serviceIds, [$serviceId]));
        } else {
            $this->serviceIds[] = $serviceId;
        }
        $this->serviceIds = array_map('intval', $this->serviceIds);
        $this->serviceId = $this->serviceIds ? (string) $this->serviceIds[0] : '';
        $this->companyUserId = '';
        $this->selectedDate = $this->firstOpenDay()->format('Y-m-d');
        $this->startTime = '';
    }

    public function continueServices(): void
    {
        $this->validate(['serviceIds' => 'required|array|min:1', 'serviceIds.*' => 'integer']);
        $this->serviceId = (string) $this->serviceIds[0];
        $this->selectedDate = $this->firstOpenDay()->format('Y-m-d');
        $this->step = 2;
    }

    public function selectPerson(int $companyUserId): void
    {
        $this->companyUserId = (string) $companyUserId;
        $this->startTime = '';
    }

    public function clearPerson(): void
    {
        $this->companyUserId = '';
        $this->startTime = '';
    }

    public function previousDay(): void
    {
        $date = Carbon::createFromFormat('Y-m-d', $this->selectedDate, 'Europe/Warsaw')->subDay();
        $today = now('Europe/Warsaw')->startOfDay();
        $now = now('Europe/Warsaw');
        $hours = $this->company->getCompanyHours();

        $daysChecked = 0;
        while ($date->gte($today) && $daysChecked < 365) {
            $dayKey = strtolower($date->format('D'));
            $dayHours = $hours[$dayKey] ?? ['closed' => true];

            $isClosed = $dayHours['closed'] ?? true;
            $isPastClose = !$isClosed && $date->isSameDay($now) && $now->format('H:i') >= ($dayHours['close'] ?? '00:00');

            if (!$isClosed && !$isPastClose) {
                break;
            }

            $date->subDay();
            $daysChecked++;
        }

        if ($date->lt($today)) {
            return;
        }

        $this->selectedDate = $date->format('Y-m-d');
        $this->startTime = '';
    }

    public function nextDay(): void
    {
        $date = Carbon::createFromFormat('Y-m-d', $this->selectedDate, 'Europe/Warsaw')->addDay();
        $hours = $this->company->getCompanyHours();

        $daysChecked = 0;
        while (($hours[strtolower($date->format('D'))]['closed'] ?? true) && $daysChecked < 365) {
            $date->addDay();
            $daysChecked++;
        }

        $this->selectedDate = $date->format('Y-m-d');
        $this->startTime = '';
    }

    public function selectTime(string $startTime): void
    {
        $this->startTime = $startTime;
        $this->autoAssignPerson();
    }

    public function nextAvailable(): void
    {
        $after = $this->startTime ? $this->startDate() : null;
        $nextStart = $this->findNextAvailableStart($this->totalDuration(), $after);

        if (!$nextStart) {
            $this->addError('startTime', 'No later available time was found.');
            return;
        }

        $this->startTime = $nextStart;
        $this->selectedDate = substr($nextStart, 0, 10);
        $this->autoAssignPerson();
    }

    public function previousAvailable(): void
    {
        $before = $this->startTime ? $this->startDate() : now('Europe/Warsaw');
        $previousStart = $this->findPreviousAvailableStart($this->totalDuration(), $before);

        if (!$previousStart) {
            $this->addError('startTime', 'No earlier available time was found.');
            return;
        }

        $this->startTime = $previousStart;
        $this->selectedDate = substr($previousStart, 0, 10);
        $this->autoAssignPerson();
    }

    public function continueBooking()
    {
        $this->validate([
            'serviceIds' => 'required|array|min:1',
            'serviceIds.*' => 'integer',
            'startTime' => 'required|date_format:Y-m-d\\TH:i',
        ]);

        if (!$this->companyUserId) {
            $this->autoAssignPerson();
        }

        $service = $this->services()->whereKey($this->serviceId)->first();
        $person = $this->peopleForSelectedServices()->whereKey($this->companyUserId)->first();

        if (!$service || !$person) {
            $this->addError('companyUserId', 'Choose a person assigned to this service.');
            return;
        }

        $start = $this->startDate();
        $end = $start->copy()->addMinutes($this->totalDuration());
        $dayHours = $this->company->getCompanyHours()[strtolower($start->format('D'))] ?? ['closed' => true];

        if (
            $start->isPast() || ($dayHours['closed'] ?? false)
            || $start->format('H:i') < ($dayHours['open'] ?? '00:00')
            || $end->format('H:i') > ($dayHours['close'] ?? '00:00')
        ) {
            $this->addError('startTime', 'Choose a time within the company opening hours.');
            return;
        }

        if (!$person->isAvailableAt($start, $end)) {
            $this->addError('startTime', 'This person is not working at the selected time.');
            return;
        }

        if (!$this->isAvailable($person->id, $start, $end)) {
            $this->addError('startTime', 'This person is already booked at the selected time.');
            return;
        }

        if (!auth()->check()) {
            session()->put($this->draftKey(), [
                'service_ids' => $this->serviceIds,
                'company_user_id' => $this->companyUserId,
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

        if (!$this->companyUserId) {
            $this->autoAssignPerson();
        }

        $service = $this->services()->whereKey($this->serviceId)->firstOrFail();
        $person = $this->peopleForSelectedServices()->whereKey($this->companyUserId)->firstOrFail();
        $start = $this->startDate();
        $end = $start->copy()->addMinutes($this->totalDuration());

        if (!$this->isAvailable($person->id, $start, $end)) {
            $this->addError('startTime', 'This person was booked by someone else. Choose another time.');
            $this->step = 2;
            return;
        }

        $reservation = Reservation::create([
            'company_id' => $this->company->id,
            'service_id' => $service->id,
            'company_user_id' => $person->id,
            'user_id' => auth()->id(),
            'client_name' => auth()->user()->name,
            'client_email' => auth()->user()->email,
            'start_time' => $start,
            'end_time' => $end,
            'status' => 'pending',
        ]);
        $reservation->services()->sync($this->serviceIds);

        $this->notifyCompanyUsers(
            'reservation',
            "Nowa rezerwacja usługi: {$service->name} od {$reservation->client_name} ({$start->format('d.m.Y H:i')})",
            $reservation->id,
            'Reservation'
        );

        session()->forget($this->draftKey());
        session()->flash('success', 'Your service booking request has been sent.');
        $this->step = 4;
    }

    private function services()
    {
        return $this->company->services()->where('is_active', true);
    }

    /**
     * Employees (company_user records) able to perform every currently selected service.
     */
    private function peopleForSelectedServices()
    {
        $query = CompanyUser::where('company_id', $this->company->id);

        foreach ($this->serviceIds as $serviceId) {
            $query->whereHas('services', fn($services) => $services->whereKey($serviceId));
        }

        return $query;
    }

    private function selectedServices()
    {
        return $this->services()->whereIn('id', $this->serviceIds)->get()->sortBy(fn($service) => array_search($service->id, $this->serviceIds))->values();
    }

    private function totalDuration(): int
    {
        return (int) $this->selectedServices()->sum(fn($service) => $service->duration + $service->buffer);
    }

    private function availablePeople(): array
    {
        if (!$this->serviceId) {
            return [];
        }

        $service = $this->services()->whereKey($this->serviceId)->first();
        if (!$service) {
            return [];
        }

        return $this->peopleForSelectedServices()->with('user')->get()->map(function (CompanyUser $companyUser) {
            return ['companyUser' => $companyUser];
        })->all();
    }

    /**
     * All free slots on the selected date, grouped by time with the people available at each slot.
     */
    private function availableTimesForSelectedDate(): array
    {
        if (!$this->selectedDate || !$this->serviceId) {
            return [];
        }

        $date = Carbon::createFromFormat('Y-m-d', $this->selectedDate, 'Europe/Warsaw')->startOfDay();
        $dayKey = strtolower($date->format('D'));
        $dayHours = $this->company->getCompanyHours()[$dayKey] ?? ['closed' => true];

        if ($dayHours['closed'] ?? false) {
            return [];
        }

        $openTime = Carbon::parse($dayHours['open'] ?? '00:00');
        $closeTime = Carbon::parse($dayHours['close'] ?? '00:00');
        $duration = $this->totalDuration();
        $now = now('Europe/Warsaw');

        $people = $this->peopleForSelectedServices()->get();
        if ($this->companyUserId) {
            $people = $people->where('id', (int) $this->companyUserId);
        }

        $slots = [];
        $candidate = $date->copy()->setTime($openTime->hour, $openTime->minute);
        $dayClose = $date->copy()->setTime($closeTime->hour, $closeTime->minute);

        while ($candidate->copy()->addMinutes($duration)->lte($dayClose)) {
            $end = $candidate->copy()->addMinutes($duration);
            $availablePeople = [];

            foreach ($people as $person) {
                if ($candidate->gt($now) && $person->isAvailableAt($candidate, $end) && $this->isAvailable($person->id, $candidate, $end)) {
                    $availablePeople[] = $person;
                }
            }

            if ($availablePeople) {
                $slots[] = [
                    'time' => $candidate->format('Y-m-d\\TH:i'),
                    'people' => $availablePeople,
                ];
            }

            $candidate->addMinutes(30);
        }

        return $slots;
    }

    private function findNextAvailableStart(int $durationMinutes, ?Carbon $after = null): ?string
    {
        $candidate = $after
            ? $this->nextSlotAfter($after)
            : now('Europe/Warsaw')->addMinutes(30)->startOfHour();
        $hours = $this->company->getCompanyHours();
        $people = $this->peopleForSelectedServices()->get();
        if ($this->companyUserId) {
            $people = $people->where('id', (int) $this->companyUserId);
        }

        for ($slot = 0; $slot < 24 * 90 * 2; $slot++) {
            $dayKey = strtolower($candidate->format('D'));
            $dayHours = $hours[$dayKey] ?? ['closed' => true];
            $end = $candidate->copy()->addMinutes($durationMinutes);

            if (
                !($dayHours['closed'] ?? false)
                && $candidate->format('H:i') >= ($dayHours['open'] ?? '00:00')
                && $end->format('H:i') <= ($dayHours['close'] ?? '00:00')
            ) {
                foreach ($people as $person) {
                    if ($person->isAvailableAt($candidate, $end) && $this->isAvailable($person->id, $candidate, $end)) {
                        return $candidate->format('Y-m-d\\TH:i');
                    }
                }
            }

            $candidate->addMinutes(30);
        }

        return null;
    }

    private function findPreviousAvailableStart(int $durationMinutes, Carbon $before): ?string
    {
        $candidate = $this->previousSlotBefore($before);
        $hours = $this->company->getCompanyHours();
        $now = now('Europe/Warsaw');
        $people = $this->peopleForSelectedServices()->get();
        if ($this->companyUserId) {
            $people = $people->where('id', (int) $this->companyUserId);
        }

        for ($slot = 0; $slot < 24 * 90 * 2; $slot++) {
            $dayKey = strtolower($candidate->format('D'));
            $dayHours = $hours[$dayKey] ?? ['closed' => true];
            $end = $candidate->copy()->addMinutes($durationMinutes);

            if (
                $candidate->greaterThan($now)
                && !($dayHours['closed'] ?? false)
                && $candidate->format('H:i') >= ($dayHours['open'] ?? '00:00')
                && $end->format('H:i') <= ($dayHours['close'] ?? '00:00')
            ) {
                foreach ($people as $person) {
                    if ($person->isAvailableAt($candidate, $end) && $this->isAvailable($person->id, $candidate, $end)) {
                        return $candidate->format('Y-m-d\\TH:i');
                    }
                }
            }

            $candidate->subMinutes(30);
        }

        return null;
    }

    private function nextSlotAfter(Carbon $time): Carbon
    {
        $candidate = $time->copy()->second(0);
        $minutesToNextSlot = 30 - ($candidate->minute % 30);

        return $candidate->addMinutes($minutesToNextSlot)->second(0);
    }

    private function previousSlotBefore(Carbon $time): Carbon
    {
        $candidate = $time->copy()->second(0);
        $minutesSincePreviousSlot = $candidate->minute % 30 ?: 30;

        return $candidate->subMinutes($minutesSincePreviousSlot)->second(0);
    }

    private function autoAssignPerson(): void
    {
        if ($this->companyUserId || !$this->startTime) {
            return;
        }

        $slot = collect($this->availableTimesForSelectedDate())->firstWhere('time', $this->startTime);
        if ($slot && $slot['people']) {
            $this->companyUserId = (string) $slot['people'][0]->id;
        }
    }

    private function firstOpenDay(): Carbon
    {
        $date = now('Europe/Warsaw')->startOfDay();
        $hours = $this->company->getCompanyHours();
        $now = now('Europe/Warsaw');

        $daysChecked = 0;
        while ($daysChecked < 365) {
            $dayKey = strtolower($date->format('D'));
            $dayHours = $hours[$dayKey] ?? ['closed' => true];

            $isClosed = $dayHours['closed'] ?? true;
            $isPastClose = !$isClosed && $date->isSameDay($now) && $now->format('H:i') >= ($dayHours['close'] ?? '00:00');

            if (!$isClosed && !$isPastClose) {
                break;
            }

            $date->addDay();
            $daysChecked++;
        }

        return $date;
    }

    private function draftKey(): string
    {
        return 'service_booking.' . $this->company->id;
    }

    private function startDate(): Carbon
    {
        return Carbon::createFromFormat('Y-m-d\\TH:i', $this->startTime, 'Europe/Warsaw');
    }

    private function isAvailable(int $companyUserId, Carbon $start, Carbon $end): bool
    {
        return !Reservation::where('company_user_id', $companyUserId)
            ->where('status', '!=', 'cancelled')
            ->where('start_time', '<', $end)
            ->where('end_time', '>', $start)
            ->exists();
    }

    public function render()
    {
        return view('livewire.company.book-service', [
            'services' => $this->services()->orderBy('name')->get(),
            'selectedService' => $this->serviceId ? $this->services()->whereKey($this->serviceId)->first() : null,
            'selectedServices' => $this->selectedServices(),
            'availablePeople' => $this->availablePeople(),
            'availableTimes' => $this->availableTimesForSelectedDate(),
        ])->layout('layouts.company', ['company' => $this->company]);
    }
}
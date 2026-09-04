<?php

namespace App\Livewire\Business;

use App\Models\Business;
use App\Models\Reservation;
use App\Models\Resource;
use Carbon\Carbon;
use Livewire\Component;

class BookService extends Component
{
    public Business $business;
    public int $step = 1;
    public string $serviceId = '';
    public array $serviceIds = [];
    public string $resourceId = '';
    public string $startTime = '';
    public int $availabilityOffset = 0;

    public function mount(Business $business): void
    {
        $this->business = $business;

        if (auth()->check() && ($draft = session()->pull($this->draftKey()))) {
            $this->serviceIds = $draft['service_ids'] ?? [$draft['service_id']];
            $this->serviceId = (string) $this->serviceIds[0];
            $this->resourceId = $draft['resource_id'];
            $this->startTime = $draft['start_time'];
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
        $this->resourceId = '';
        $this->startTime = '';
        $this->availabilityOffset = 0;
    }

    public function continueServices(): void
    {
        $this->validate(['serviceIds' => 'required|array|min:1', 'serviceIds.*' => 'integer']);
        $this->serviceId = (string) $this->serviceIds[0];
        $this->step = 2;
    }

    public function selectPerson(int $resourceId, string $suggestedStart): void
    {
        $this->resourceId = (string) $resourceId;
        $this->startTime = $suggestedStart;
        $this->availabilityOffset = 0;
    }

    public function shiftAvailableTimes(int $direction): void
    {
        $this->availabilityOffset = max(0, $this->availabilityOffset + $direction);
    }

    public function selectTime(string $startTime): void
    {
        $this->startTime = $startTime;
    }

    public function nextAvailable(): void
    {
        $this->validate([
            'resourceId' => 'required|integer',
        ]);

        $service = $this->services()->whereKey($this->serviceId)->firstOrFail();
        $person = Resource::findOrFail($this->resourceId);
        $after = $this->startTime ? $this->startDate() : null;

        $nextStart = $this->findNextAvailableStart($person, $this->totalDuration(), $after);

        if (!$nextStart) {
            $this->addError('startTime', 'No later available time was found.');
            return;
        }

        $this->startTime = $nextStart;
    }

    public function previousAvailable(): void
    {
        $this->validate([
            'resourceId' => 'required|integer',
        ]);

        $person = Resource::findOrFail($this->resourceId);
        $before = $this->startTime ? $this->startDate() : now('Europe/Warsaw');
        $previousStart = $this->findPreviousAvailableStart($person, $this->totalDuration(), $before);

        if (!$previousStart) {
            $this->addError('startTime', 'No earlier available time was found.');
            return;
        }

        $this->startTime = $previousStart;
    }

    public function continueBooking()
    {
        $this->validate([
            'serviceIds' => 'required|array|min:1',
            'serviceIds.*' => 'integer',
            'resourceId' => 'required|integer',
            'startTime' => 'required|date_format:Y-m-d\\TH:i',
        ]);

        $service = $this->services()->whereKey($this->serviceId)->first();
        $person = $this->peopleForSelectedServices()->whereKey($this->resourceId)->first();

        if (!$service || !$person) {
            $this->addError('resourceId', 'Choose a person assigned to this service.');
            return;
        }

        $person = Resource::findOrFail($person->id);

        $start = $this->startDate();
        $end = $start->copy()->addMinutes($this->totalDuration());
        $dayHours = $this->business->getBusinessHours()[strtolower($start->format('D'))] ?? ['closed' => true];

        if ($start->isPast() || ($dayHours['closed'] ?? false)
            || $start->format('H:i') < ($dayHours['open'] ?? '00:00')
            || $end->format('H:i') > ($dayHours['close'] ?? '00:00')) {
            $this->addError('startTime', 'Choose a time within the business opening hours.');
            return;
        }

        if (!$person->isAvailableAt($start, $end)) {
            $this->addError('startTime', 'This person is not working at the selected time.');
            return;
        }

        if (!$this->isAvailable($person->id, $start, $end) || !$person->isAvailableAt($start, $end)) {
            $this->addError('startTime', 'This person is already booked at the selected time.');
            return;
        }

        if (!auth()->check()) {
            session()->put($this->draftKey(), [
                'service_ids' => $this->serviceIds,
                'resource_id' => $this->resourceId,
                'start_time' => $this->startTime,
            ]);

            return redirect()->guest(route('login.subdomain', ['business' => $this->business]));
        }

        $this->step = 3;
    }

    public function confirmBooking(): void
    {
        if (!auth()->check()) {
            redirect()->guest(route('login.subdomain', ['business' => $this->business]));
            return;
        }

        $service = $this->services()->whereKey($this->serviceId)->firstOrFail();
        $person = $this->peopleForSelectedServices()->whereKey($this->resourceId)->firstOrFail();
        $person = Resource::findOrFail($person->id);
        $start = $this->startDate();
        $end = $start->copy()->addMinutes($this->totalDuration());

        if (!$this->isAvailable($person->id, $start, $end)) {
            $this->addError('startTime', 'This person was booked by someone else. Choose another time.');
            $this->step = 2;
            return;
        }

        $reservation = Reservation::create([
            'business_id' => $this->business->id,
            'service_id' => $service->id,
            'resource_id' => $person->id,
            'user_id' => auth()->id(),
            'client_name' => auth()->user()->name,
            'client_email' => auth()->user()->email,
            'start_time' => $start,
            'end_time' => $end,
            'status' => 'pending',
        ]);
        $reservation->services()->sync($this->serviceIds);

        session()->forget($this->draftKey());
        session()->flash('success', 'Your service booking request has been sent.');
        $this->step = 4;
    }

    public function render()
    {
        return view('livewire.business.book-service', [
            'services' => $this->services()->orderBy('name')->get(),
            'selectedService' => $this->serviceId ? $this->services()->whereKey($this->serviceId)->first() : null,
            'selectedServices' => $this->selectedServices(),
            'availablePeople' => $this->availablePeople(),
            'availableTimes' => $this->availableTimes(),
        ])->layout('layouts.business', ['business' => $this->business]);
    }

    private function services()
    {
        return $this->business->services()->where('is_active', true);
    }

    private function peopleForService(int $serviceId)
    {
        return $this->business->resources()
            ->where('type', 'person')
            ->where('is_active', true)
            ->whereHas('services', fn ($query) => $query->whereKey($serviceId));
    }

    private function peopleForSelectedServices()
    {
        $query = $this->business->resources()->where('type', 'person')->where('is_active', true);

        foreach ($this->serviceIds as $serviceId) {
            $query->whereHas('services', fn ($services) => $services->whereKey($serviceId));
        }

        return $query;
    }

    private function selectedServices()
    {
        return $this->services()->whereIn('id', $this->serviceIds)->get()->sortBy(fn ($service) => array_search($service->id, $this->serviceIds))->values();
    }

    private function totalDuration(): int
    {
        return (int) $this->selectedServices()->sum(fn ($service) => $service->duration + $service->buffer);
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

        return $this->peopleForSelectedServices()->get()->map(function ($person) {
            $resource = Resource::find($person->id);

            return [
                'resource' => $resource,
                'nextStart' => $this->findNextAvailableStart($resource, $this->totalDuration()),
            ];
        })->all();
    }

    private function availableTimes(): array
    {
        if (!$this->resourceId || !$this->serviceId) {
            return [];
        }

        $resource = Resource::find($this->resourceId);
        if (!$resource) {
            return [];
        }

        $times = [];
        $after = null;
        $duration = $this->totalDuration();

        for ($index = 0; $index < $this->availabilityOffset + 5; $index++) {
            $nextStart = $this->findNextAvailableStart($resource, $duration, $after);
            if (!$nextStart) {
                break;
            }

            $times[] = $nextStart;
            $after = Carbon::createFromFormat('Y-m-d\\TH:i', $nextStart, 'Europe/Warsaw');
        }

        return array_slice($times, $this->availabilityOffset, 5);
    }

    private function findNextAvailableStart(Resource $resource, int $durationMinutes, ?Carbon $after = null): ?string
    {
        $candidate = $after
            ? $this->nextSlotAfter($after)
            : now('Europe/Warsaw')->addMinutes(30)->startOfHour();
        $hours = $this->business->getBusinessHours();

        for ($slot = 0; $slot < 24 * 90 * 2; $slot++) {
            $dayKey = strtolower($candidate->format('D'));
            $dayHours = $hours[$dayKey] ?? ['closed' => true];
            $end = $candidate->copy()->addMinutes($durationMinutes);

            if (!($dayHours['closed'] ?? false)
                && $candidate->format('H:i') >= ($dayHours['open'] ?? '00:00')
                && $end->format('H:i') <= ($dayHours['close'] ?? '00:00')
                && $resource->isAvailableAt($candidate, $end)
                && $this->isAvailable($resource->id, $candidate, $end)) {
                return $candidate->format('Y-m-d\\TH:i');
            }

            $candidate->addMinutes(30);
        }

        return null;
    }

    private function findPreviousAvailableStart(Resource $resource, int $durationMinutes, Carbon $before): ?string
    {
        $candidate = $this->previousSlotBefore($before);
        $hours = $this->business->getBusinessHours();
        $now = now('Europe/Warsaw');

        for ($slot = 0; $slot < 24 * 90 * 2; $slot++) {
            $dayKey = strtolower($candidate->format('D'));
            $dayHours = $hours[$dayKey] ?? ['closed' => true];
            $end = $candidate->copy()->addMinutes($durationMinutes);

            if ($candidate->greaterThan($now)
                && !($dayHours['closed'] ?? false)
                && $candidate->format('H:i') >= ($dayHours['open'] ?? '00:00')
                && $end->format('H:i') <= ($dayHours['close'] ?? '00:00')
                && $resource->isAvailableAt($candidate, $end)
                && $this->isAvailable($resource->id, $candidate, $end)) {
                return $candidate->format('Y-m-d\\TH:i');
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

    private function draftKey(): string
    {
        return 'service_booking.' . $this->business->id;
    }

    private function startDate(): Carbon
    {
        return Carbon::createFromFormat('Y-m-d\\TH:i', $this->startTime, 'Europe/Warsaw');
    }

    private function isAvailable(int $resourceId, Carbon $start, Carbon $end): bool
    {
        return !Reservation::where('resource_id', $resourceId)
            ->where('status', '!=', 'cancelled')
            ->where('start_time', '<', $end)
            ->where('end_time', '>', $start)
            ->exists();
    }
}
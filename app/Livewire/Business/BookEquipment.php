<?php

namespace App\Livewire\Business;

use App\Models\Business;
use App\Models\Resource;
use App\Models\ResourceBooking;
use Carbon\Carbon;
use Livewire\Component;

class BookEquipment extends Component
{
    public Business $business;
    public int $step = 1;
    public array $resourceIds = [];
    public string $durationHours = '1';
    public string $startTime = '';
    public int $availabilityOffset = 0;

    public function mount(Business $business): void
    {
        $this->business = $business;

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
        $this->startTime = '';
        $this->availabilityOffset = 0;
    }

    public function continueResources(): void
    {
        $this->validate(['resourceIds' => 'required|array|min:1', 'resourceIds.*' => 'integer']);
        $this->step = 2;
    }

    public function selectTime(string $startTime): void
    {
        $this->startTime = $startTime;
    }

    public function shiftAvailableTimes(int $direction): void
    {
        $this->availabilityOffset = max(0, $this->availabilityOffset + $direction);
    }

    public function nextAvailable(): void
    {
        $this->validate([
            'resourceIds' => 'required|array|min:1',
            'durationHours' => 'required|integer|min:1|max:24',
        ]);

        $after = $this->startTime
            ? $this->startDate()->addMinutes(30)
            : null;

        $this->startTime = $this->findNextAvailableStart(
            $this->resourceIds,
            (int) $this->durationHours,
            $after
        );
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

        ResourceBooking::create([
            'business_id' => $this->business->id,
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

        session()->forget($this->draftKey());
        session()->flash('success', 'Your booking request has been sent.');
        $this->step = 4;
    }

    public function render()
    {
        return view('livewire.business.resource-booking', [
            'equipment' => $this->equipment()->orderBy('name')->get(),
            'selectedResources' => $this->resourceIds ? Resource::whereIn('id', $this->resourceIds)->get() : collect(),
            'availableTimes' => $this->availableTimes(),
        ])->layout('layouts.business', ['business' => $this->business]);
    }

    private function equipment()
    {
        return $this->business->resources()->where('type', 'equipment')->where('is_active', true);
    }

    private function draftKey(): string
    {
        return 'equipment_booking.' . $this->business->id;
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
        foreach (Resource::whereIn('id', $this->resourceIds)->get() as $resource) {
            if (!$resource->isAvailableAt($start, $end) || !$this->isAvailable($resource->id, $start, $end)) {
                return false;
            }
        }
        return true;
    }

    private function totalPrice(): float
    {
        return (float) Resource::whereIn('id', $this->resourceIds)->sum('hourly_rate') * (int) $this->durationHours;
    }

    private function availableTimes(): array
    {
        if (!$this->resourceIds || !$this->durationHours) {
            return [];
        }

        $times = [];
        $after = null;
        for ($index = 0; $index < $this->availabilityOffset + 5; $index++) {
            $nextStart = $this->findNextAvailableStart($this->resourceIds, (int) $this->durationHours, $after);
            if (!$nextStart) {
                break;
            }
            $times[] = $nextStart;
            $after = Carbon::createFromFormat('Y-m-d\\TH:i', $nextStart, 'Europe/Warsaw');
        }

        return array_slice($times, $this->availabilityOffset, 5);
    }

    private function findNextAvailableStart(array $resourceIds, int $durationHours, ?Carbon $after = null): string
    {
        if ($after) {
            $candidate = $after->copy()->addMinutes(30);

            if ($candidate->minute > 0) {
                $candidate->addHour()->startOfHour();
            }
        } else {
            $candidate = now('Europe/Warsaw')->addMinutes(30)->startOfHour();
        }
        $hours = $this->business->getBusinessHours();

        for ($slot = 0; $slot < 24 * 90 * 2; $slot++) {
            $dayKey = strtolower($candidate->format('D'));
            $dayHours = $hours[$dayKey] ?? ['closed' => true];
            $end = $candidate->copy()->addHours($durationHours);

            if (!($dayHours['closed'] ?? false)
                && $candidate->format('H:i') >= ($dayHours['open'] ?? '00:00')
                && $end->format('H:i') <= ($dayHours['close'] ?? '00:00')
                && $this->resourcesAreAvailableAt($resourceIds, $candidate, $end)) {
                return $candidate->format('Y-m-d\\TH:i');
            }

            $candidate->addMinutes(30);
        }

        return now('Europe/Warsaw')->addDay()->format('Y-m-d\\TH:i');
    }

    private function resourcesAreAvailableAt(array $resourceIds, Carbon $start, Carbon $end): bool
    {
        foreach (Resource::whereIn('id', $resourceIds)->get() as $resource) {
            if (!$resource->isAvailableAt($start, $end) || !$this->isAvailable($resource->id, $start, $end)) {
                return false;
            }
        }
        return true;
    }
}
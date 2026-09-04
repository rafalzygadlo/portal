<?php

namespace Tests\Feature;

use App\Livewire\Business\BookService;
use App\Livewire\Business\BookEquipment;
use App\Models\Business;
use App\Models\Reservation;
use App\Models\Resource;
use App\Models\ResourceBooking;
use App\Models\Service;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class BusinessBookingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Carbon::setTestNow(Carbon::parse('2026-09-04 08:00:00', 'Europe/Warsaw'));
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();
        parent::tearDown();
    }

    public function test_resource_is_available_inside_its_schedule(): void
    {
        $business = Business::factory()->create(['business_hours' => $this->businessHours()]);
        $resource = Resource::factory()->create([
            'business_id' => $business->id,
            'working_hours' => $this->businessHours(),
        ]);

        $this->assertTrue($resource->isAvailableAt($this->time('09:00'), $this->time('10:00')));
        $this->assertFalse($resource->isAvailableAt($this->time('16:30'), $this->time('17:30')));
    }

    public function test_resource_inherits_business_schedule_when_no_custom_schedule_exists(): void
    {
        $business = Business::factory()->create(['business_hours' => $this->businessHours()]);
        $resource = Resource::factory()->create(['business_id' => $business->id, 'working_hours' => null]);

        $this->assertSame($business->getBusinessHours(), $resource->getWorkingHours());
    }

    public function test_closed_day_makes_resource_unavailable(): void
    {
        $hours = $this->businessHours();
        $hours['fri']['closed'] = true;
        $business = Business::factory()->create(['business_hours' => $hours]);
        $resource = Resource::factory()->create(['business_id' => $business->id, 'working_hours' => $hours]);

        $this->assertFalse($resource->isAvailableAt($this->time('10:00'), $this->time('11:00')));
    }

    public function test_time_off_makes_resource_unavailable_for_the_whole_day(): void
    {
        $business = Business::factory()->create(['business_hours' => $this->businessHours()]);
        $resource = Resource::factory()->create([
            'business_id' => $business->id,
            'working_hours' => $this->businessHours(),
            'unavailable_periods' => [['start' => '2026-09-04', 'end' => '2026-09-04']],
        ]);

        $this->assertFalse($resource->isAvailableAt($this->time('10:00'), $this->time('11:00')));
    }

    public function test_overlapping_resource_booking_is_detected(): void
    {
        $resource = Resource::factory()->create();
        ResourceBooking::create([
            'resource_id' => $resource->id,
            'business_id' => $resource->business_id,
            'start_time' => $this->time('10:00'),
            'end_time' => $this->time('12:00'),
            'status' => 'confirmed',
            'client_name' => 'Client',
        ]);

        $this->assertDatabaseHas('resource_bookings', ['resource_id' => $resource->id]);
        $this->assertTrue(ResourceBooking::where('resource_id', $resource->id)
            ->where('start_time', '<', $this->time('11:00'))
            ->where('end_time', '>', $this->time('11:30'))
            ->exists());
    }

    public function test_adjacent_resource_bookings_do_not_overlap(): void
    {
        $resource = Resource::factory()->create();
        ResourceBooking::create([
            'resource_id' => $resource->id,
            'business_id' => $resource->business_id,
            'start_time' => $this->time('10:00'),
            'end_time' => $this->time('11:00'),
            'client_name' => 'Client',
        ]);

        $this->assertFalse(ResourceBooking::where('resource_id', $resource->id)
            ->where('start_time', '<', $this->time('12:00'))
            ->where('end_time', '>', $this->time('11:00'))
            ->exists());
    }

    public function test_cancelled_resource_booking_does_not_block_a_new_booking(): void
    {
        $resource = Resource::factory()->create();
        ResourceBooking::create([
            'resource_id' => $resource->id,
            'business_id' => $resource->business_id,
            'status' => 'cancelled',
            'start_time' => $this->time('10:00'),
            'end_time' => $this->time('12:00'),
            'client_name' => 'Client',
        ]);

        $this->assertFalse(ResourceBooking::where('resource_id', $resource->id)
            ->where('status', '!=', 'cancelled')
            ->where('start_time', '<', $this->time('11:00'))
            ->where('end_time', '>', $this->time('11:30'))
            ->exists());
    }

    public function test_only_people_assigned_to_all_selected_services_are_eligible(): void
    {
        $business = Business::factory()->create();
        $first = Service::create(['business_id' => $business->id, 'name' => 'Oil change', 'duration' => 60, 'buffer' => 0, 'price' => 0, 'is_active' => true]);
        $second = Service::create(['business_id' => $business->id, 'name' => 'Inspection', 'duration' => 30, 'buffer' => 0, 'price' => 0, 'is_active' => true]);
        $mechanic = Resource::factory()->create(['business_id' => $business->id, 'type' => 'person']);
        $other = Resource::factory()->create(['business_id' => $business->id, 'type' => 'person']);
        $first->resources()->attach([$mechanic->id, $other->id]);
        $second->resources()->attach($mechanic->id);

        $this->assertTrue($mechanic->services()->whereKey($first->id)->exists());
        $this->assertTrue($mechanic->services()->whereKey($second->id)->exists());
        $this->assertFalse($other->services()->whereKey($second->id)->exists());
    }

    public function test_multi_service_booking_stores_every_selected_service(): void
    {
        $user = User::factory()->create();
        $business = Business::factory()->create(['business_hours' => $this->businessHours()]);
        $first = Service::create(['business_id' => $business->id, 'name' => 'Oil change', 'duration' => 60, 'buffer' => 0, 'price' => 0, 'is_active' => true]);
        $second = Service::create(['business_id' => $business->id, 'name' => 'Inspection', 'duration' => 30, 'buffer' => 0, 'price' => 0, 'is_active' => true]);
        $person = Resource::factory()->create(['business_id' => $business->id, 'type' => 'person', 'working_hours' => $this->businessHours()]);
        $first->resources()->attach($person->id);
        $second->resources()->attach($person->id);

        Livewire::actingAs($user)->test(BookService::class, ['business' => $business])
            ->set('serviceIds', [$first->id, $second->id])
            ->set('serviceId', (string) $first->id)
            ->set('resourceId', (string) $person->id)
            ->set('startTime', '2026-09-04T10:00')
            ->call('confirmBooking');

        $reservation = Reservation::latest('id')->first();
        $this->assertNotNull($reservation);
        $this->assertEquals(90, $reservation->start_time->diffInMinutes($reservation->end_time));
        $this->assertEqualsCanonicalizing([$first->id, $second->id], $reservation->services()->pluck('services.id')->all());
    }

    public function test_previous_available_uses_the_total_duration_of_selected_services(): void
    {
        $hours = $this->businessHours();
        foreach ($hours as $day => $dayHours) {
            $hours[$day]['close'] = '14:30';
        }
        $business = Business::factory()->create(['business_hours' => $hours]);
        $first = Service::create(['business_id' => $business->id, 'name' => 'Oil change', 'duration' => 60, 'buffer' => 0, 'price' => 0, 'is_active' => true]);
        $second = Service::create(['business_id' => $business->id, 'name' => 'Inspection', 'duration' => 30, 'buffer' => 0, 'price' => 0, 'is_active' => true]);
        $person = Resource::factory()->create(['business_id' => $business->id, 'type' => 'person', 'working_hours' => $hours]);
        $first->resources()->attach($person->id);
        $second->resources()->attach($person->id);

        Reservation::create([
            'business_id' => $business->id,
            'service_id' => $first->id,
            'resource_id' => $person->id,
            'client_name' => 'Existing client',
            'client_email' => 'existing@example.com',
            'start_time' => $this->time('12:30'),
            'end_time' => $this->time('13:30'),
            'status' => 'confirmed',
        ]);

        Livewire::test(BookService::class, ['business' => $business])
            ->set('serviceIds', [$first->id, $second->id])
            ->set('serviceId', (string) $first->id)
            ->set('resourceId', (string) $person->id)
            ->set('startTime', '2026-09-04T14:00')
            ->call('previousAvailable')
            ->assertSet('startTime', '2026-09-04T11:00');
    }

    public function test_previous_available_keeps_the_slot_time_when_moving_to_the_previous_day(): void
    {
        $business = Business::factory()->create(['business_hours' => $this->businessHours()]);
        $service = Service::create(['business_id' => $business->id, 'name' => 'Meeting', 'duration' => 60, 'buffer' => 0, 'price' => 0, 'is_active' => true]);
        $person = Resource::factory()->create(['business_id' => $business->id, 'type' => 'person', 'working_hours' => $this->businessHours()]);
        $service->resources()->attach($person->id);

        Livewire::test(BookService::class, ['business' => $business])
            ->set('serviceIds', [$service->id])
            ->set('serviceId', (string) $service->id)
            ->set('resourceId', (string) $person->id)
            ->set('startTime', '2026-09-05T12:30')
            ->call('previousAvailable')
            ->assertSet('startTime', '2026-09-05T12:00');
    }

    public function test_book_service_rejects_a_time_when_the_person_is_already_reserved(): void
    {
        $business = Business::factory()->create(['business_hours' => $this->businessHours()]);
        $service = Service::create(['business_id' => $business->id, 'name' => 'Meeting', 'duration' => 60, 'buffer' => 0, 'price' => 0, 'is_active' => true]);
        $person = Resource::factory()->create(['business_id' => $business->id, 'type' => 'person', 'working_hours' => $this->businessHours()]);
        $service->resources()->attach($person->id);
        Reservation::create([
            'business_id' => $business->id,
            'service_id' => $service->id,
            'resource_id' => $person->id,
            'client_name' => 'Existing client',
            'client_email' => 'existing@example.com',
            'start_time' => $this->time('10:00'),
            'end_time' => $this->time('11:00'),
            'status' => 'confirmed',
        ]);

        Livewire::test(BookService::class, ['business' => $business])
            ->set('serviceIds', [$service->id])
            ->set('serviceId', (string) $service->id)
            ->set('resourceId', (string) $person->id)
            ->set('startTime', '2026-09-04T10:30')
            ->call('continueBooking')
            ->assertHasErrors('startTime');
    }

    public function test_book_service_exposes_five_available_times_and_allows_selecting_one(): void
    {
        $business = Business::factory()->create(['business_hours' => $this->businessHours()]);
        $service = Service::create(['business_id' => $business->id, 'name' => 'Meeting', 'duration' => 60, 'buffer' => 0, 'price' => 0, 'is_active' => true]);
        $person = Resource::factory()->create(['business_id' => $business->id, 'type' => 'person', 'working_hours' => $this->businessHours()]);
        $service->resources()->attach($person->id);

        Livewire::test(BookService::class, ['business' => $business])
            ->set('serviceIds', [$service->id])
            ->set('serviceId', (string) $service->id)
            ->set('resourceId', (string) $person->id)
            ->assertViewHas('availableTimes', fn (array $times) => count($times) === 5)
            ->call('shiftAvailableTimes', 1)
            ->assertSet('availabilityOffset', 1)
            ->call('shiftAvailableTimes', -1)
            ->assertSet('availabilityOffset', 0)
            ->call('selectTime', '2026-09-04T10:00')
            ->assertSet('startTime', '2026-09-04T10:00');
    }

    public function test_equipment_booking_can_include_multiple_items_and_calculates_total_price(): void
    {
        $user = User::factory()->create();
        $business = Business::factory()->create(['business_hours' => $this->businessHours()]);
        $first = Resource::factory()->create(['business_id' => $business->id, 'type' => 'equipment', 'hourly_rate' => 25]);
        $second = Resource::factory()->create(['business_id' => $business->id, 'type' => 'equipment', 'hourly_rate' => 15]);

        Livewire::actingAs($user)->test(BookEquipment::class, ['business' => $business])
            ->set('resourceIds', [$first->id, $second->id])
            ->set('durationHours', '2')
            ->set('startTime', '2026-09-04T10:00')
            ->call('confirmBooking');

        $booking = ResourceBooking::latest('id')->first();
        $this->assertNotNull($booking);
        $this->assertEqualsCanonicalizing([$first->id, $second->id], $booking->resource_ids);
        $this->assertEquals(80, $booking->total_price);
    }

    private function businessHours(): array
    {
        return [
            'mon' => ['open' => '09:00', 'close' => '17:00', 'closed' => false],
            'tue' => ['open' => '09:00', 'close' => '17:00', 'closed' => false],
            'wed' => ['open' => '09:00', 'close' => '17:00', 'closed' => false],
            'thu' => ['open' => '09:00', 'close' => '17:00', 'closed' => false],
            'fri' => ['open' => '09:00', 'close' => '17:00', 'closed' => false],
            'sat' => ['open' => '10:00', 'close' => '14:00', 'closed' => false],
            'sun' => ['closed' => true],
        ];
    }

    private function time(string $time): Carbon
    {
        return Carbon::createFromFormat('Y-m-d H:i', '2026-09-04 ' . $time, 'Europe/Warsaw');
    }
}

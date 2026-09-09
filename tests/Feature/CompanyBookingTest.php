<?php

namespace Tests\Feature;

use App\Livewire\Company\BookService;
use App\Livewire\Company\BookResource;
use App\Models\Company;
use App\Models\CompanyUser;
use App\Models\Reservation;
use App\Models\Resource;
use App\Models\ResourceBooking;
use App\Models\Service;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CompanyBookingTest extends TestCase
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

    public function test_overlapping_resource_booking_is_detected(): void
    {
        $resource = Resource::factory()->create();
        ResourceBooking::create([
            'resource_id' => $resource->id,
            'company_id' => $resource->company_id,
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
            'company_id' => $resource->company_id,
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
            'company_id' => $resource->company_id,
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
        $company = Company::factory()->create();
        $first = Service::create(['company_id' => $company->id, 'name' => 'Oil change', 'duration' => 60, 'buffer' => 0, 'price' => 0, 'is_active' => true]);
        $second = Service::create(['company_id' => $company->id, 'name' => 'Inspection', 'duration' => 30, 'buffer' => 0, 'price' => 0, 'is_active' => true]);

        $mechanicUser = User::factory()->create();
        $otherUser = User::factory()->create();
        $company->users()->attach($mechanicUser, ['owner' => false]);
        $company->users()->attach($otherUser, ['owner' => false]);

        $mechanicCompanyUser = $this->companyUserFor($company, $mechanicUser);
        $otherCompanyUser = $this->companyUserFor($company, $otherUser);
        $mechanicCompanyUser->services()->attach([$first->id, $second->id]);
        $otherCompanyUser->services()->attach($first->id);

        $this->assertTrue($mechanicCompanyUser->services()->whereKey($first->id)->exists());
        $this->assertTrue($mechanicCompanyUser->services()->whereKey($second->id)->exists());
        $this->assertFalse($otherCompanyUser->services()->whereKey($second->id)->exists());
    }

    public function test_multi_service_booking_stores_every_selected_service(): void
    {
        $user = User::factory()->create();
        $company = Company::factory()->create(['company_hours' => $this->companyHours()]);
        $first = Service::create(['company_id' => $company->id, 'name' => 'Oil change', 'duration' => 60, 'buffer' => 0, 'price' => 0, 'is_active' => true]);
        $second = Service::create(['company_id' => $company->id, 'name' => 'Inspection', 'duration' => 30, 'buffer' => 0, 'price' => 0, 'is_active' => true]);
        $person = $this->makeQualifiedPerson($company, [$first->id, $second->id], ['working_hours' => $this->companyHours()]);

        Livewire::actingAs($user)->test(BookService::class, ['company' => $company])
            ->set('serviceIds', [$first->id, $second->id])
            ->set('serviceId', (string) $first->id)
            ->set('companyUserId', (string) $person->id)
            ->set('startTime', '2026-09-04T10:00')
            ->call('confirmBooking');

        $reservation = Reservation::latest('id')->first();
        $this->assertNotNull($reservation);
        $this->assertEquals(90, $reservation->start_time->diffInMinutes($reservation->end_time));
        $this->assertEqualsCanonicalizing([$first->id, $second->id], $reservation->services()->pluck('services.id')->all());
    }

    public function test_previous_available_uses_the_total_duration_of_selected_services(): void
    {
        $hours = $this->companyHours();
        foreach ($hours as $day => $dayHours) {
            $hours[$day]['close'] = '14:30';
        }
        $company = Company::factory()->create(['company_hours' => $hours]);
        $first = Service::create(['company_id' => $company->id, 'name' => 'Oil change', 'duration' => 60, 'buffer' => 0, 'price' => 0, 'is_active' => true]);
        $second = Service::create(['company_id' => $company->id, 'name' => 'Inspection', 'duration' => 30, 'buffer' => 0, 'price' => 0, 'is_active' => true]);
        $person = $this->makeQualifiedPerson($company, [$first->id, $second->id], ['working_hours' => $hours]);

        Reservation::create([
            'company_id' => $company->id,
            'service_id' => $first->id,
            'company_user_id' => $person->id,
            'client_name' => 'Existing client',
            'client_email' => 'existing@example.com',
            'start_time' => $this->time('12:30'),
            'end_time' => $this->time('13:30'),
            'status' => 'confirmed',
        ]);

        Livewire::test(BookService::class, ['company' => $company])
            ->set('serviceIds', [$first->id, $second->id])
            ->set('serviceId', (string) $first->id)
            ->set('companyUserId', (string) $person->id)
            ->set('startTime', '2026-09-04T14:00')
            ->call('previousAvailable')
            ->assertSet('startTime', '2026-09-04T11:00');
    }

    public function test_previous_available_keeps_the_slot_time_when_moving_to_the_previous_day(): void
    {
        $company = Company::factory()->create(['company_hours' => $this->companyHours()]);
        $service = Service::create(['company_id' => $company->id, 'name' => 'Meeting', 'duration' => 60, 'buffer' => 0, 'price' => 0, 'is_active' => true]);
        $person = $this->makeQualifiedPerson($company, [$service->id], ['working_hours' => $this->companyHours()]);

        Livewire::test(BookService::class, ['company' => $company])
            ->set('serviceIds', [$service->id])
            ->set('serviceId', (string) $service->id)
            ->set('companyUserId', (string) $person->id)
            ->set('startTime', '2026-09-05T12:30')
            ->call('previousAvailable')
            ->assertSet('startTime', '2026-09-05T12:00');
    }

    public function test_book_service_rejects_a_time_when_the_person_is_already_reserved(): void
    {
        $company = Company::factory()->create(['company_hours' => $this->companyHours()]);
        $service = Service::create(['company_id' => $company->id, 'name' => 'Meeting', 'duration' => 60, 'buffer' => 0, 'price' => 0, 'is_active' => true]);
        $person = $this->makeQualifiedPerson($company, [$service->id], ['working_hours' => $this->companyHours()]);
        Reservation::create([
            'company_id' => $company->id,
            'service_id' => $service->id,
            'company_user_id' => $person->id,
            'client_name' => 'Existing client',
            'client_email' => 'existing@example.com',
            'start_time' => $this->time('10:00'),
            'end_time' => $this->time('11:00'),
            'status' => 'confirmed',
        ]);

        Livewire::test(BookService::class, ['company' => $company])
            ->set('serviceIds', [$service->id])
            ->set('serviceId', (string) $service->id)
            ->set('companyUserId', (string) $person->id)
            ->set('startTime', '2026-09-04T10:30')
            ->call('continueBooking')
            ->assertHasErrors('startTime');
    }

    public function test_book_service_exposes_five_available_times_and_allows_selecting_one(): void
    {
        $company = Company::factory()->create(['company_hours' => $this->companyHours()]);
        $service = Service::create(['company_id' => $company->id, 'name' => 'Meeting', 'duration' => 60, 'buffer' => 0, 'price' => 0, 'is_active' => true]);
        $person = $this->makeQualifiedPerson($company, [$service->id], ['working_hours' => $this->companyHours()]);

        Livewire::test(BookService::class, ['company' => $company])
            ->set('serviceIds', [$service->id])
            ->set('serviceId', (string) $service->id)
            ->set('companyUserId', (string) $person->id)
            ->call('selectDate', '2026-09-04')
            ->assertViewHas('availableTimes', fn (array $times) => count($times) > 0)
            ->call('selectTime', '2026-09-04T10:00')
            ->assertSet('startTime', '2026-09-04T10:00');
    }

    public function test_equipment_booking_can_include_multiple_items_and_calculates_total_price(): void
    {
        $user = User::factory()->create();
        $company = Company::factory()->create(['company_hours' => $this->companyHours()]);
        $first = Resource::factory()->create(['company_id' => $company->id, 'type' => 'equipment', 'hourly_rate' => 25]);
        $second = Resource::factory()->create(['company_id' => $company->id, 'type' => 'equipment', 'hourly_rate' => 15]);

        Livewire::actingAs($user)->test(BookResource::class, ['company' => $company])
            ->set('resourceIds', [$first->id, $second->id])
            ->set('durationHours', '2')
            ->set('startTime', '2026-09-04T10:00')
            ->call('confirmBooking');

        $booking = ResourceBooking::latest('id')->first();
        $this->assertNotNull($booking);
        $this->assertEqualsCanonicalizing([$first->id, $second->id], $booking->resource_ids);
        $this->assertEquals(80, $booking->total_price);
    }

    private function companyUserFor(Company $company, User $user): CompanyUser
    {
        if (!$company->users()->whereKey($user->id)->exists()) {
            $company->users()->attach($user, ['owner' => false]);
        }

        return CompanyUser::where('company_id', $company->id)->where('user_id', $user->id)->firstOrFail();
    }

    private function makeQualifiedPerson(Company $company, array $serviceIds, array $companyUserAttributes = []): CompanyUser
    {
        $user = User::factory()->create();
        $companyUser = $this->companyUserFor($company, $user);
        $companyUser->services()->attach($serviceIds);
        $companyUser->update($companyUserAttributes);

        return $companyUser;
    }

    private function companyHours(): array
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

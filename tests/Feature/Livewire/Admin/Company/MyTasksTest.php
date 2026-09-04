<?php

namespace Tests\Feature\Livewire\Admin\Company;

use App\Livewire\Admin\Company\MyTasks\Index;
use App\Models\Company;
use App\Models\Reservation;
use App\Models\Resource;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class MyTasksTest extends TestCase
{
    use RefreshDatabase;

    public function test_worker_sees_only_reservations_for_assigned_resources(): void
    {
        $owner = User::factory()->create();
        $worker = User::factory()->create();
        $otherWorker = User::factory()->create();
        $company = Company::factory()->create();
        $company->users()->attach($owner, ['owner' => true]);
        $company->users()->attach($worker, ['owner' => false]);
        $company->users()->attach($otherWorker, ['owner' => false]);

        $workerResource = Resource::factory()->create([
            'company_id' => $company->id,
            'type' => 'person',
            'assigned_user_id' => $worker->id,
            'name' => 'Pan Mechanik',
        ]);
        $otherResource = Resource::factory()->create([
            'company_id' => $company->id,
            'type' => 'person',
            'assigned_user_id' => $otherWorker->id,
            'name' => 'Other Worker',
        ]);
        $service = Service::create([
            'company_id' => $company->id,
            'name' => 'Usługa testowa',
            'duration' => 30,
            'buffer' => 0,
            'price' => 100,
            'is_active' => true,
        ]);

        Reservation::create([
            'company_id' => $company->id,
            'service_id' => $service->id,
            'resource_id' => $workerResource->id,
            'client_name' => 'Worker client',
            'client_email' => 'client1@example.test',
            'start_time' => now()->addHour(),
            'end_time' => now()->addHours(2),
            'status' => 'confirmed',
        ]);
        Reservation::create([
            'company_id' => $company->id,
            'service_id' => $service->id,
            'resource_id' => $otherResource->id,
            'client_name' => 'Other client',
            'client_email' => 'client2@example.test',
            'start_time' => now()->addHour(),
            'end_time' => now()->addHours(2),
            'status' => 'confirmed',
        ]);

        $this->actingAs($worker);

        Livewire::test(Index::class, ['company' => $company])
            ->assertSee('Worker client')
            ->assertDontSee('Other client')
            ->assertSee('Pan Mechanik');
    }
}
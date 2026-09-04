<?php

namespace Tests\Feature\Livewire\Admin\Company;

use App\Livewire\Admin\Company\Resource\Index;
use App\Models\Company;
use App\Models\Resource;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ResourceIndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_resources_are_grouped_by_type(): void
    {
        $user = User::factory()->create();
        $company = Company::factory()->create();
        $company->users()->attach($user, ['owner' => true]);

        Resource::factory()->create([
            'company_id' => $company->id,
            'name' => 'Jan Kowalski',
            'type' => 'person',
        ]);

        Resource::factory()->create([
            'company_id' => $company->id,
            'name' => 'Sala 101',
            'type' => 'facility',
        ]);

        Resource::factory()->create([
            'company_id' => $company->id,
            'name' => 'Projektor',
            'type' => 'equipment',
            'hourly_rate' => 25.50,
        ]);

        $this->actingAs($user);

        Livewire::test(Index::class, ['company' => $company])
            ->assertSee('People')
            ->assertSee('Facilities')
            ->assertSee('Equipment')
            ->assertSee('Jan Kowalski')
            ->assertSee('Sala 101')
            ->assertSee('Projektor')
            ->assertSee('25.50 PLN/h');
    }
}

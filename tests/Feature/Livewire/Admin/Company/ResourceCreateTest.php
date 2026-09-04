<?php

namespace Tests\Feature\Livewire\Admin\Company;

use App\Livewire\Admin\Company\Resource\Create;
use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ResourceCreateTest extends TestCase
{
    use RefreshDatabase;

    public function test_person_resource_can_be_assigned_to_a_company_user(): void
    {
        $company = Company::factory()->create();
        $user = User::factory()->create(['first_name' => 'Jan', 'last_name' => 'Mechanik']);
        $company->users()->attach($user, ['owner' => false]);

        Livewire::test(Create::class, ['company' => $company])
            ->set('name', 'Pan Mechanik')
            ->set('type', 'person')
            ->set('assignedUserId', $user->id)
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('resources', [
            'company_id' => $company->id,
            'name' => 'Pan Mechanik',
            'type' => 'person',
            'assigned_user_id' => $user->id,
        ]);
    }

    public function test_person_resource_cannot_be_assigned_to_a_user_from_another_company(): void
    {
        $company = Company::factory()->create();
        $otherCompany = Company::factory()->create();
        $user = User::factory()->create();
        $otherCompany->users()->attach($user, ['owner' => false]);

        Livewire::test(Create::class, ['company' => $company])
            ->set('name', 'Pan Mechanik')
            ->set('type', 'person')
            ->set('assignedUserId', $user->id)
            ->call('save')
            ->assertHasErrors('assignedUserId');

        $this->assertDatabaseMissing('resources', [
            'company_id' => $company->id,
            'assigned_user_id' => $user->id,
        ]);
    }
}
<?php

namespace Tests\Feature\Livewire\Admin\Company;

use App\Livewire\Admin\Company\Service\Assign;
use App\Livewire\Admin\Company\Service\Index;
use App\Models\Company;
use App\Models\CompanyUser;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ServiceAssignTest extends TestCase
{
    use RefreshDatabase;

    public function test_service_list_shows_assigned_employees(): void
    {
        $owner = User::factory()->create();
        $employee = User::factory()->create(['first_name' => 'Jan', 'last_name' => 'Kowalski']);
        $company = Company::factory()->create();
        $company->users()->attach($owner, ['owner' => true]);
        $company->users()->attach($employee, ['owner' => false, 'display_name' => 'Jan the Mechanic']);

        $service = Service::create([
            'company_id' => $company->id,
            'name' => 'Oil change',
            'description' => 'Professional oil change',
            'duration' => 60,
            'buffer' => 15,
            'price' => 100,
            'is_active' => true,
        ]);

        $companyUser = CompanyUser::where('company_id', $company->id)->where('user_id', $employee->id)->firstOrFail();
        $service->companyUsers()->attach($companyUser->id);

        $this->actingAs($owner);

        Livewire::test(Index::class, ['company' => $company])
            ->assertSee('Jan the Mechanic');
    }

    public function test_company_admin_can_assign_employees_to_a_service(): void
    {
        $owner = User::factory()->create();
        $employee = User::factory()->create(['first_name' => 'Jan', 'last_name' => 'Kowalski']);
        $company = Company::factory()->create();
        $company->users()->attach($owner, ['owner' => true]);
        $company->users()->attach($employee, ['owner' => false]);

        $service = Service::create([
            'company_id' => $company->id,
            'name' => 'Oil change',
            'description' => 'Professional oil change',
            'duration' => 60,
            'buffer' => 15,
            'price' => 100,
            'is_active' => true,
        ]);

        $this->actingAs($owner);

        Livewire::test(Assign::class, ['company' => $company])
            ->call('openAssign', $service->id)
            ->assertSet('open', true)
            ->set('userIds', [(string) $employee->id])
            ->call('save')
            ->assertSet('open', false);

        $companyUser = CompanyUser::where('company_id', $company->id)->where('user_id', $employee->id)->firstOrFail();
        $this->assertTrue($service->companyUsers()->whereKey($companyUser->id)->exists());
    }

    public function test_company_admin_can_remove_employee_assignment(): void
    {
        $owner = User::factory()->create();
        $employee = User::factory()->create(['first_name' => 'Jan', 'last_name' => 'Kowalski']);
        $company = Company::factory()->create();
        $company->users()->attach($owner, ['owner' => true]);
        $company->users()->attach($employee, ['owner' => false]);

        $service = Service::create([
            'company_id' => $company->id,
            'name' => 'Oil change',
            'description' => 'Professional oil change',
            'duration' => 60,
            'buffer' => 15,
            'price' => 100,
            'is_active' => true,
        ]);

        $companyUser = CompanyUser::where('company_id', $company->id)->where('user_id', $employee->id)->firstOrFail();
        $service->companyUsers()->attach($companyUser->id);

        $this->actingAs($owner);

        Livewire::test(Assign::class, ['company' => $company])
            ->call('openAssign', $service->id)
            ->assertSet('userIds', [(string) $employee->id])
            ->set('userIds', [])
            ->call('save')
            ->assertSet('open', false);

        $this->assertFalse($service->companyUsers()->whereKey($companyUser->id)->exists());
    }
}
<?php

namespace Tests\Feature\Livewire\Admin\Company;

use App\Livewire\Admin\Company\User\Index;
use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class UserIndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_company_admin_can_see_users_assigned_to_the_company(): void
    {
        $owner = User::factory()->create([
            'first_name' => 'Anna',
            'last_name' => 'Owner',
            'email' => 'anna@example.test',
        ]);
        $member = User::factory()->create(['first_name' => 'Marek', 'last_name' => 'Member']);
        $otherUser = User::factory()->create(['first_name' => 'Ola', 'last_name' => 'Other']);
        $company = Company::factory()->create();
        $otherCompany = Company::factory()->create();

        $company->users()->attach($owner, ['owner' => true]);
        $company->users()->attach($member, ['owner' => false]);
        $otherCompany->users()->attach($otherUser, ['owner' => false]);

        $this->actingAs($owner);

        Livewire::test(Index::class, ['company' => $company])
            ->assertSee('Anna Owner')
            ->assertSee('anna@example.test')
            ->assertSee('Marek Member')
            ->assertDontSee('Ola Other')
            ->assertSee('Owner')
            ->assertSee('User');
    }

    public function test_non_owner_cannot_open_the_company_user_list(): void
    {
        $owner = User::factory()->create(['email_verified_at' => now()]);
        $otherUser = User::factory()->create(['email_verified_at' => now()]);
        $company = Company::factory()->create();
        $company->users()->attach($owner, ['owner' => true]);

        $this->actingAs($otherUser)
            ->get(route('admin.company.users', ['company' => $company]))
            ->assertForbidden();
    }

    public function test_company_admin_can_attach_an_existing_user(): void
    {
        $owner = User::factory()->create();
        $employee = User::factory()->create();
        $company = Company::factory()->create();
        $company->users()->attach($owner, ['owner' => true]);

        $this->actingAs($owner);

        Livewire::test(Index::class, ['company' => $company])
            ->set('attachEmail', $employee->email)
            ->call('attachUser')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('company_user', [
            'company_id' => $company->id,
            'user_id' => $employee->id,
            'owner' => false,
        ]);
    }

    public function test_company_admin_can_create_and_attach_a_new_user(): void
    {
        $owner = User::factory()->create();
        $company = Company::factory()->create();
        $company->users()->attach($owner, ['owner' => true]);

        $this->actingAs($owner);

        Livewire::test(Index::class, ['company' => $company])
            ->set('firstName', 'Jan')
            ->set('lastName', 'Mechanik')
            ->set('email', 'jan.mechanik@example.test')
            ->set('password', 'temporary-password')
            ->call('createUser')
            ->assertHasNoErrors();

        $employee = User::where('email', 'jan.mechanik@example.test')->firstOrFail();

        $this->assertDatabaseHas('company_user', [
            'company_id' => $company->id,
            'user_id' => $employee->id,
            'owner' => false,
        ]);
    }

    public function test_attach_shows_an_error_when_email_does_not_exist(): void
    {
        $owner = User::factory()->create();
        $company = Company::factory()->create();
        $company->users()->attach($owner, ['owner' => true]);

        $this->actingAs($owner);

        Livewire::test(Index::class, ['company' => $company])
            ->set('attachEmail', 'missing@example.test')
            ->call('attachUser')
            ->assertHasErrors(['attachEmail' => ['Sorry, user does not exist.']]);
    }
}
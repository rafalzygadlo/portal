<?php

namespace Tests\Feature;

use App\Livewire\Company\Create;
use App\Livewire\Company\Edit;
use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CompanyFormTest extends TestCase
{
    use RefreshDatabase;

    public function test_company_create_component_renders_and_validates_required_fields(): void
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(Create::class)
            ->set('name', 'ab')
            ->set('subdomain', 'x')
            ->call('save')
            ->assertHasErrors(['name', 'subdomain']);
    }

    public function test_company_edit_component_can_load_existing_company(): void
    {
        $user = User::factory()->create();
        $company = Company::factory()->create(['name' => 'Example Company', 'subdomain' => 'example-company']);
        $company->users()->attach($user->id, ['owner' => true]);

        Livewire::actingAs($user)
            ->test(Edit::class, ['company' => $company])
            ->assertSet('name', 'Example Company')
            ->assertSet('subdomain', 'example-company');
    }
}

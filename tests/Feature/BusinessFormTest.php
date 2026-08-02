<?php

namespace Tests\Feature;

use App\Livewire\Business\Create;
use App\Livewire\Business\Edit;
use App\Models\Business;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class BusinessFormTest extends TestCase
{
    use RefreshDatabase;

    public function test_business_create_component_renders_and_validates_required_fields(): void
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(Create::class)
            ->set('name', 'ab')
            ->set('subdomain', 'x')
            ->call('save')
            ->assertHasErrors(['name', 'subdomain']);
    }

    public function test_business_edit_component_can_load_existing_business(): void
    {
        $user = User::factory()->create();
        $business = Business::factory()->create(['name' => 'Example Business', 'subdomain' => 'example-business']);
        $business->users()->attach($user->id, ['owner' => true]);

        Livewire::actingAs($user)
            ->test(Edit::class, ['business' => $business])
            ->assertSet('name', 'Example Business')
            ->assertSet('subdomain', 'example-business');
    }
}

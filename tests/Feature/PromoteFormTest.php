<?php

namespace Tests\Feature;

use App\Livewire\GlobalModal;
use App\Models\Offer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class PromoteFormTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_open_the_promote_modal_with_a_model(): void
    {
        $offer = Offer::factory()->create();

        Livewire::test(GlobalModal::class)
            ->call('open', 'promote-form', 'Promote', true, ['model' => $offer])
            ->assertSet('view', 'promote-form')
            ->assertSet('isOpen', true)
            ->assertSee('Promuj i Zapłać');
    }
}

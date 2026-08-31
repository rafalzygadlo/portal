<?php

namespace Tests\Feature\Livewire;

use App\Livewire\Promote;
use App\Models\Offer;
use App\Models\Promotion;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class PromoteTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_reflects_active_promotion_status(): void
    {
        $offer = Offer::factory()->create();

        Promotion::factory()->create([
            'promotable_id' => $offer->id,
            'promotable_type' => Offer::class,
        ]);

        Livewire::test(Promote::class, ['model' => $offer])
            ->assertSet('isPromoted', true);
    }

    public function test_it_updates_status_on_promotion_updated_event(): void
    {
        $offer = Offer::factory()->create();

        Livewire::test(Promote::class, ['model' => $offer])
            ->assertSet('isPromoted', false)
            ->call('updatePromotionStatus')
            ->assertSet('isPromoted', false);

        Promotion::factory()->create([
            'promotable_id' => $offer->id,
            'promotable_type' => Offer::class,
        ]);

        Livewire::test(Promote::class, ['model' => $offer->fresh()])
            ->dispatch('promotionUpdated')
            ->assertSet('isPromoted', true);
    }

    public function test_open_promote_form_dispatches_modal_event(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $offer = Offer::factory()->create(['user_id' => $user->id]);

        Livewire::actingAs($user)
            ->test(Promote::class, ['model' => $offer])
            ->call('openPromoteForm')
            ->assertDispatched('openModal');
    }
}

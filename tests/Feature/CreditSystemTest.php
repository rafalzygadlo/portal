<?php

namespace Tests\Feature;

use App\Livewire\PromoteForm;
use App\Models\Offer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CreditSystemTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_deducts_user_credits_when_promoting_an_offer(): void
    {
        $user = User::factory()->create([
            'credits' => 100,
        ]);

        $offer = Offer::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->actingAs($user);

        Livewire::test(PromoteForm::class, [
            'modelId' => $offer->id,
            'modelClass' => Offer::class,
        ])
            ->call('submitPromotion');

        $user->refresh();

        $this->assertSame(93, $user->credits);
        $this->assertDatabaseHas('promotions', [
            'promotable_id' => $offer->id,
            'promotable_type' => Offer::class,
        ]);
        $this->assertDatabaseHas('credit_transactions', [
            'user_id' => $user->id,
            'type' => 'promotion',
            'amount' => -7,
        ]);
    }

    public function test_it_rewards_the_referrer_when_a_user_uses_a_referral_code(): void
    {
        $referrer = User::factory()->create([
            'credits' => 0,
            'referral_code' => 'ABC123',
        ]);

        $user = User::factory()->create([
            'credits' => 0,
        ]);

        $user->applyReferralCode('ABC123');

        $this->assertSame($referrer->id, $user->referred_by_user_id);
        $this->assertSame(50, $referrer->fresh()->credits);
        $this->assertDatabaseHas('credit_transactions', [
            'user_id' => $referrer->id,
            'type' => 'referral',
            'amount' => 50,
        ]);
    }

    public function test_it_gives_a_welcome_bonus_on_first_login(): void
    {
        $user = User::factory()->create([
            'credits' => 0,
            'welcome_bonus_received' => false,
        ]);

        $this->assertTrue($user->grantWelcomeBonusIfNeeded());
        $this->assertSame(50, $user->fresh()->credits);
        $this->assertDatabaseHas('credit_transactions', [
            'user_id' => $user->id,
            'type' => 'welcome_bonus',
            'amount' => 50,
        ]);
    }

    public function test_it_rejects_a_second_referral_code_for_the_same_user(): void
    {
        $referrerA = User::factory()->create([
            'credits' => 0,
            'referral_code' => 'AAA111',
        ]);

        $referrerB = User::factory()->create([
            'credits' => 0,
            'referral_code' => 'BBB222',
        ]);

        $user = User::factory()->create([
            'credits' => 0,
            'referred_by_user_id' => null,
        ]);

        $this->assertTrue($user->applyReferralCode('AAA111'));
        $this->assertFalse($user->applyReferralCode('BBB222'));
        $this->assertSame($referrerA->id, $user->fresh()->referred_by_user_id);
    }
}

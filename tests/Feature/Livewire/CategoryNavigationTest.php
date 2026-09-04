<?php

namespace Tests\Feature\Livewire;

use App\Livewire\Breadcrumb;
use App\Livewire\CategoryBar;
use App\Livewire\GlobalModal;
use App\Livewire\Promote;
use App\Models\Business;
use App\Models\Category;
use App\Models\Offer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CategoryNavigationTest extends TestCase
{
    use RefreshDatabase;

    public function test_category_bar_shows_root_categories_without_a_selection(): void
    {
        $root = Category::factory()->create(['name' => 'Root category']);
        $child = Category::factory()->create(['parent_id' => $root->id]);

        Livewire::test(CategoryBar::class, [
            'selectEvent' => 'business-category-selected',
            'orientation' => 'horizontal',
        ])
            ->assertSee($root->name)
            ->assertDontSee($child->name)
            ->assertSet('orientation', 'horizontal');
    }

    public function test_category_bar_shows_children_for_selected_parent(): void
    {
        $root = Category::factory()->create();
        $child = Category::factory()->create(['parent_id' => $root->id]);

        Livewire::test(CategoryBar::class, [
            'currentCategory' => $root,
            'selectEvent' => 'offer-category-selected',
        ])
            ->assertSee($child->name)
            ->assertDontSee($root->name);
    }

    public function test_category_bar_shows_siblings_for_selected_leaf(): void
    {
        $root = Category::factory()->create();
        $selected = Category::factory()->create(['parent_id' => $root->id]);
        $sibling = Category::factory()->create(['parent_id' => $root->id]);

        Livewire::test(CategoryBar::class, [
            'currentCategory' => $selected,
            'selectEvent' => 'business-category-selected',
        ])
            ->assertSee($sibling->name)
            ->assertSee('Back');
    }

    public function test_breadcrumb_builds_the_category_path(): void
    {
        $root = Category::factory()->create(['name' => 'Root']);
        $child = Category::factory()->create(['name' => 'Child', 'parent_id' => $root->id]);
        $leaf = Category::factory()->create(['name' => 'Leaf', 'parent_id' => $child->id]);

        Livewire::test(Breadcrumb::class, [
            'selectEvent' => 'offer-category-selected',
            'category' => $leaf,
        ])
            ->assertSee('Root')
            ->assertSee('Child')
            ->assertSee('Leaf');
    }

    public function test_breadcrumb_dispatches_the_selected_parent_category(): void
    {
        $root = Category::factory()->create();
        $child = Category::factory()->create(['parent_id' => $root->id]);
        $leaf = Category::factory()->create(['parent_id' => $child->id]);

        Livewire::test(Breadcrumb::class, [
            'selectEvent' => 'business-category-selected',
            'category' => $leaf,
        ])
            ->assertSeeHtml('wire:click.prevent="$dispatch(\'business-category-selected\'');
    }

    public function test_business_index_filters_by_category_and_descendants(): void
    {
        $root = Category::factory()->create();
        $child = Category::factory()->create(['parent_id' => $root->id]);
        $included = Business::factory()->create(['name' => 'Included business']);
        $excluded = Business::factory()->create(['name' => 'Excluded business']);
        $included->categories()->attach($child);
        $excluded->categories()->attach(Category::factory()->create());

        Livewire::test(\App\Livewire\Business\Index::class)
            ->set('categorySlug', $root->slug)
            ->assertSee($included->name)
            ->assertDontSee($excluded->name);
    }

    public function test_offer_index_filters_by_category_and_descendants(): void
    {
        $root = Category::factory()->create();
        $child = Category::factory()->create(['parent_id' => $root->id]);
        $included = Offer::factory()->create(['title' => 'Included offer']);
        $excluded = Offer::factory()->create(['title' => 'Excluded offer']);
        $included->categories()->attach($child);
        $excluded->categories()->attach(Category::factory()->create());

        Livewire::test(\App\Livewire\Offer\Index::class)
            ->set('categorySlug', $root->slug)
            ->assertSee($included->title)
            ->assertDontSee($excluded->title);
    }

    public function test_promote_dispatches_modal_parameters_without_using_reserved_params_name(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $offer = Offer::factory()->create(['user_id' => $user->id]);

        Livewire::actingAs($user)
            ->test(Promote::class, ['model' => $offer])
            ->call('openPromoteForm')
            ->assertDispatched('openModal', fn ($name, $params) =>
                $name === 'openModal'
                && $params['view'] === 'promote-form'
                && $params['title'] === 'Promuj swoją treść'
                && $params['modalParams']['modelId'] === $offer->id
                && $params['modalParams']['modelClass'] === Offer::class
            );
    }

    public function test_global_modal_restores_intended_modal_after_authentication(): void
    {
        $offer = Offer::factory()->create();

        session()->put('intended_modal', [
            'component' => 'promote-form',
            'title' => 'Promote',
            'params' => [
                'modelId' => $offer->id,
                'modelClass' => Offer::class,
            ],
        ]);

        Livewire::test(GlobalModal::class)
            ->call('authChanged')
            ->assertSet('isOpen', true)
            ->assertSet('view', 'promote-form')
            ->assertSet('title', 'Promote')
            ->assertSet('params', [
                'modelId' => $offer->id,
                'modelClass' => Offer::class,
            ]);
    }
}

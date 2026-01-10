<?php

namespace Tests\Feature\Livewire;

use App\Livewire\Page;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class PageTest extends TestCase
{
    public function test_renders_successfully()
    {
        Livewire::test(Page::class)
            ->assertStatus(200);
    }
}

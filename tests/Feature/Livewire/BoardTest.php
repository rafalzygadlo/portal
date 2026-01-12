<?php

namespace Tests\Feature\Livewire;

use App\Livewire\Board;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class BoardTest extends TestCase
{
    public function test_renders_successfully()
    {
        Livewire::test(Board::class)
            ->assertStatus(200);
    }
}

<?php

namespace Tests\Feature\Livewire;

use App\Livewire\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class UserTest extends TestCase
{
    public function test_renders_successfully()
    {
        Livewire::test(User::class)
            ->assertStatus(200);
    }
}

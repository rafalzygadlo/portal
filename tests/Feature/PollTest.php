<?php

namespace Tests\Feature;

use App\Models\Poll\Poll;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PollTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_it_can_view_polls_index_page()
    {
        if (!config('modules_poll')) {    
            $this->markTestSkipped('Nie uruchamiamy testu, ponieważ moduł Poll jest wyłączony w konfiguracji.');
        }

        Poll::factory(3)->create();
            $response = $this->get('/polls');
            $response->assertStatus(200);
        
    }

    /** @test */
    public function test_it_can_view_poll_show_page()
    {
        if (!config('modules_poll')) {
            $this->markTestSkipped('Nie uruchamiamy testu, ponieważ moduł Poll jest wyłączony w konfiguracji.');
        }

        $poll = Poll::factory()->create();

        $response = $this->get("/polls/{$poll->id}");

        $response->assertStatus(200);
    }
}

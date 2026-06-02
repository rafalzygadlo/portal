<?php

namespace Tests\Feature;

use App\Models\Todo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TodoTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_view_todos_index_page()
    {
        Todo::factory(3)->create();
        
        $response = $this->get('/todos');
        
        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_view_todo_show_page()
    {
        $todo = Todo::factory()->create();
        
        $response = $this->get("/todos/{$todo->slug}");
        
        $response->assertStatus(200);
    }
}

<?php

namespace Tests\Unit\Models;

use App\Models\Todo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TodoTest extends TestCase
{
    use RefreshDatabase;

    public function test_status_color_mapping(): void
    {
        $this->assertSame('warning', Todo::factory()->make(['status' => 'pending'])->getStatusColor());
        $this->assertSame('info', Todo::factory()->make(['status' => 'planned'])->getStatusColor());
        $this->assertSame('success', Todo::factory()->make(['status' => 'completed'])->getStatusColor());
        $this->assertSame('secondary', Todo::factory()->make(['status' => 'unknown'])->getStatusColor());
    }
}

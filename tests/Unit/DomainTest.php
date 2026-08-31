<?php

namespace Tests\Unit;

use App\Support\Domain;
use Illuminate\Http\Request;
use Tests\TestCase;

class DomainTest extends TestCase
{

    /** @test */
    public function test_session_domain_defaults_to_parent_domain(): void
    {
        config(['app.business_domain' => 'localhost']);

        $this->assertSame('.localhost', config('session.domain'));
    }
}

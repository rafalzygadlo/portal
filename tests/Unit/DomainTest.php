<?php

namespace Tests\Unit;

use App\Support\Domain;
use Illuminate\Http\Request;
use Tests\TestCase;

class DomainTest extends TestCase
{
    /** @test */
    public function test_it_detects_subdomain_from_request_host(): void
    {
        config(['app.business_domain' => 'localhost']);

        $request = Request::create('http://marcin.localhost/');

        $this->assertSame('marcin', Domain::subdomainFromRequest($request));
        $this->assertTrue(Domain::isSubdomainRequest($request));
    }

    /** @test */
    public function test_it_returns_null_for_main_domain(): void
    {
        config(['app.business_domain' => 'localhost']);

        $request = Request::create('http://localhost/');

        $this->assertNull(Domain::subdomainFromRequest($request));
        $this->assertFalse(Domain::isSubdomainRequest($request));
    }

    /** @test */
    public function test_session_domain_defaults_to_parent_domain(): void
    {
        config(['app.business_domain' => 'localhost']);

        $this->assertSame('.localhost', config('session.domain'));
    }
}

<?php

namespace Tests\Feature;

use App\Models\Company;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CompanyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_it_should_return_a_successful_response_for_the_homepage()
    {
        Company::factory()->create();
        
        $response = $this->get('/');
        
        $response->assertStatus(200);
    }

    /** @test */
    public function test_it_can_view_company_index_page()
    {
        Company::factory(2)->create();
        
        $response = $this->get('/companies');
        
        $response->assertStatus(200);
    }

    /** @test */
    public function test_it_can_view_company_show_page()
    {
        $company = Company::factory()->create();
        
        $response = $this->get("/company/{$company->subdomain}");
        
        $response->assertStatus(200);
    }
}

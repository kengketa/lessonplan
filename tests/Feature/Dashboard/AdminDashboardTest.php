<?php

namespace Tests\Feature\Dashboard;

use Inertia\Testing\Assert;
use Tests\Setup\RefreshDatabase;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_page_viewing_required_authenication()
    {
        $response = $this->get(route('dashboard'));
        $response->assertSessionHasNoErrors();
        $response->assertStatus(302);
    }

    public function test_dashboard_page_can_be_viewed_after_login()
    {
        $this->signInAdmin();
        $response = $this->get(route('dashboard'));
        $response->assertSessionHasNoErrors();
        $response->assertStatus(200);
        $pageUrl = $response->getOriginalContent()->getData()['page']['url'];
        $this->assertEquals('/dashboard', $pageUrl);
    }
}

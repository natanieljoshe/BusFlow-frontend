<?php

namespace Tests\Feature;

use Tests\TestCase;

class FrontendUiTest extends TestCase
{
    /**
     * Test Guest user redirection to login for admin/dashboard pages.
     */
    public function test_guest_user_redirection_to_login()
    {
        // When guest accesses admin dashboard, they should be redirected
        $response = $this->get('/admin/dashboard');
        $response->assertRedirect('/login');
    }

    /**
     * Test Operator sidebar link visibility.
     * Operator sees Routes and Boarding Scanner, but NOT Fleet and Users.
     */
    public function test_operator_sidebar_link_visibility()
    {
        // Mock a logged in operator user in session
        $response = $this->withSession([
            'user' => [
                'role' => 'operator',
                'name' => 'Operator Test'
            ],
            'api_token' => 'mock_token'
        ])->get('/admin/dashboard');

        $response->assertStatus(200);
        
        // Operator should see Routes and Boarding Scanner links
        $response->assertSee('Routes');
        $response->assertSee('Boarding Scanner');

        // Operator should NOT see Fleet and Users links
        $response->assertDontSee('Fleet Management');
        $response->assertDontSee('Users & RBAC');
    }

    /**
     * Test Operator restriction redirects/blocks from admin/fleet and admin/users.
     */
    public function test_operator_access_restrictions()
    {
        // When logged in as operator, accessing fleet or users page directly is restricted
        $responseFleet = $this->withSession([
            'user' => [
                'role' => 'operator',
                'name' => 'Operator Test'
            ],
            'api_token' => 'mock_token'
        ])->get('/admin/fleet');
        
        $responseFleet->assertStatus(403);

        $responseUsers = $this->withSession([
            'user' => [
                'role' => 'operator',
                'name' => 'Operator Test'
            ],
            'api_token' => 'mock_token'
        ])->get('/admin/users');

        $responseUsers->assertStatus(403);
    }

    /**
     * Test Wallet page UI rendering check (contains mock balance top-up inputs).
     */
    public function test_wallet_page_ui_rendering()
    {
        $response = $this->withSession([
            'user' => [
                'role' => 'passenger',
                'name' => 'Passenger Test'
            ],
            'api_token' => 'mock_token'
        ])->get('/user/wallet');

        $response->assertStatus(200);
        $response->assertSee('amount');
        $response->assertSee('top-up');
    }

    /**
     * Test Halte page UI Leaflet map rendering check (contains map container).
     */
    public function test_halte_page_leaflet_map_rendering()
    {
        $response = $this->withSession([
            'user' => [
                'role' => 'admin',
                'name' => 'Admin Test'
            ],
            'api_token' => 'mock_token'
        ])->get('/admin/haltes');

        $response->assertStatus(200);
        
        // Asserting that it contains the map container
        $response->assertSee('id="map"');
    }
}

<?php

namespace Tests\Feature;

use App\Models\Properti;
use App\Models\User;
use Tests\TestCase;

class RouteTest extends TestCase
{
    public function test_public_routes_return_200(): void
    {
        $routes = ['/', '/tentang', '/properti', '/login', '/register'];
        foreach ($routes as $route) {
            $response = $this->get($route);
            $response->assertStatus(200);
        }
    }

    public function test_properti_detail_with_valid_id(): void
    {
        $properti = Properti::first();
        $this->assertNotNull($properti);
        $this->get('/properti/'.$properti->id)->assertStatus(200);
    }

    public function test_role_route_requires_auth(): void
    {
        $this->get('/role')->assertRedirect('/login');
    }

    public function test_account_routes_require_auth(): void
    {
        $this->get('/account')->assertRedirect('/login');
        $this->get('/account/sewa')->assertRedirect('/login');
        $this->get('/account/pemilik')->assertRedirect('/login');
        $this->get('/account/pemilik/properti')->assertRedirect('/login');
    }

    public function test_authenticated_user_can_access_account(): void
    {
        $user = User::where('role', 'penyewa')->first();
        $this->assertNotNull($user);
        $this->actingAs($user)->get('/account')->assertStatus(200);
    }
}

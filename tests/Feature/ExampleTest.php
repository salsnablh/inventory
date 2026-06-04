<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_page_loads()
    {
        $response = $this->actingAs(User::factory()->create())->get('/');

        $response->assertStatus(200);
        $response->assertSee('Inventory - Salsa');
    }

    public function test_guest_is_redirected_to_login()
    {
        $response = $this->get('/');

        $response->assertRedirect('/login');
    }

    public function test_health_endpoint_returns_service_status()
    {
        $response = $this->get('/health');

        $response->assertStatus(200);
        $response->assertJsonPath('status', 'ok');
    }

    public function test_inventory_recording_page_loads()
    {
        $response = $this->actingAs(User::factory()->create())->get('/pencatatan');

        $response->assertStatus(200);
        $response->assertSee('Pencatatan Barang dan Stok');
    }
}

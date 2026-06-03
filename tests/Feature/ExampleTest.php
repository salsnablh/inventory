<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_page_loads()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Ringkasan Inventory');
    }

    public function test_health_endpoint_returns_service_status()
    {
        $response = $this->get('/health');

        $response->assertStatus(200);
        $response->assertJsonPath('status', 'ok');
    }

    public function test_inventory_recording_page_loads()
    {
        $response = $this->get('/pencatatan');

        $response->assertStatus(200);
        $response->assertSee('Pencatatan Barang dan Stok');
    }
}

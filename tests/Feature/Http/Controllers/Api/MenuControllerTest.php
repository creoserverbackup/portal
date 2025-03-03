<?php

namespace Tests\Feature\Http\Controllers\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MenuControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_main()
    {
        $response = $this->get('/api/menus/main', ['webshop' => true]);
        $response->dump();

        $response->assertStatus(200);
    }

    public function test_category()
    {
        $response = $this->json('get', '/api/menus/category', ['max_lvl' => 0], ['webshop' => true]);
        $response->dump();

        $response->assertStatus(200);
    }
}

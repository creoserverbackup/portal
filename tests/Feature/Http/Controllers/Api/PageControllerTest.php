<?php

namespace Tests\Feature\Http\Controllers\Api;

use Tests\TestCase;

class PageControllerTest extends TestCase
{
    public function test_privacy()
    {
        $response = $this->json(
            'get',
            '/api/pages/privacy',
            [],
            [
                'webshop' => true
            ]
        );


        $response->dump();
        $response->assertStatus(200);
    }

    public function test_home()
    {
        $response = $this->json(
            'get',
            '/api/pages/home',
            ['exclude'=>['attributes']],
            [
                'webshop' => true
            ]
        );


        $response->dump();
        $response->assertStatus(200);
    }

    public function test_about()
    {
        $response = $this->json(
            'get',
            '/api/pages/about',
            [],
            [
                'webshop' => true
            ]
        );


        $response->dump();
        $response->assertStatus(200);
    }
}

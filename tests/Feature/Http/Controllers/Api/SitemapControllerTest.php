<?php

namespace Tests\Feature\Http\Controllers\Api;

use Tests\TestCase;

class SitemapControllerTest extends TestCase
{
    public function test_list_page_url()
    {
        $response = $this->json(
            'get',
            '/api/sitemaps/list-page-url',
            [],
            [
                'webshop' => true
            ]
        );


        $response->dump();
        $response->assertStatus(200);
    }
}

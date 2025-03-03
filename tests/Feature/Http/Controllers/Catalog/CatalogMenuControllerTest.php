<?php

namespace Tests\Feature\Http\Controllers\Catalog;

use Tests\TestCase;

class CatalogMenuControllerTest extends TestCase
{

    public function test_index()
    {

        $response = $this->json(
            'get',
            '/menu/category',
        );
        $response->dump();
        $response->assertStatus(200);
    }

}

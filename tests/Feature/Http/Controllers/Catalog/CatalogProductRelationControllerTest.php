<?php

namespace Tests\Feature\Http\Controllers\Catalog;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CatalogProductRelationControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        $response = $this->json('get', '/catalog-products/relations', ['product_id'=>'83']);

        $response->dump();

        $response->assertStatus(200);
    }
}

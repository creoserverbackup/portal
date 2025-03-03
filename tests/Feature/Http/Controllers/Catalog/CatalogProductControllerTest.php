<?php

namespace Tests\Feature\Http\Controllers\Catalog;

use Tests\TestCase;

class CatalogProductControllerTest extends TestCase
{

    public function test_index()
    {
//        $response = $this->json('get', '/catalog-products/new-index', ['product_size' => '2U']);
//        $response->dump();
//        $response->assertStatus(200);
//
//        $response = $this->json('get', '/catalog-products/new-index', ['product_size' => ['2U','HDD']]);
//        $response->dump();
//        $response->assertStatus(200);


//       $cp = CatalogProduct::find(83);
//       dd($cp);

        $response = $this->json(
            'get',
            'api/catalog/catalog-products',
            [
//                'c_29_ids'=>[668],
//                'a_21_ids' => [17],
//                'price_to' => 300,
//                'a_7_from'=>50,
//                'a_7_to'=>65,
//                'category_id' => '116',
//            'search'=>'dell',
//                'limit' => 60
            ],
            ['webshop'=>true]
        );
        $response->dump();
        $response->assertStatus(200);
    }

    public function test_show()
    {
        $response = $this->json('get', '/catalog-products/10251');

        $response->dump();

        $response->assertStatus(200);
    }

    public function test_autocomplete()
    {
//        lga 1366 - attribute_value_id = 24

        $response = $this->json(
            'get',
            '/catalog-products/autocomplete',
            [

//                'c_29_ids'=>[668],
//                'a_21_ids' => [17],
//                'price_to' => 300,
//                'a_7_from'=>50,
//                'a_7_to'=>65,
//                'category_id' => '116',
//                'search'=>'lga 1366',
                'search'=>'lga 1366',
//                'mark_id' => 6,
                'limit' => 60
            ]
        );
        $response->dump();
        $response->assertStatus(200);
    }


}

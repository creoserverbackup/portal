<?php

namespace Tests\Feature\Http\Controllers\Api;

use Tests\TestCase;

class AliasControllerTest extends TestCase
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

        $response = $this->json(
            'get',
//            '/api/alias/servers/server/sompathc/hp-proliant-dl160-11test1111222-g6-81',
//            '/api/alias/servers/server',
//            '/api/alias/main-39/servers-40/dell-poweredge-servers-41/dell-poweredge-r6-44',
//            '/api/alias/dell-poweredge-r6-44',
            '/api/aliases',
//            '/api/alias/servers/server/test-333-test-666-10040',
//            '/api/alias/servers/server/test-333-test-666-10040',
//            '/api/alias/servers/server',
            [
//                'path' => 'creoserver-115/refurbished-server-parts/cpu-processor/'
//                'path' => 'main-39/servers-40/hp-proliant-servers-48/hp-proliant-dl5-49'
//                'path' => 'main-39/storages-56/dell-poweredge-storages-57/dell-poweredge-r730-58'
//                'path' => 'creoserver-115/refurbished-server-parts/finisar-ftlx8571d3bcl-10gbase-srsw-sfp-744'
//                'path' => 'creoserver-115/refurbished-server/hp-proliant-dl160-g8-sff-83',
//                'path' => 'creoserver-115/refurbished-server/dell-poweredge-r740-g14-lff-547',
                'path' => 'some-path/test-about'
            ],
            [
                'webshop' => true
            ]
        );



        $response->dump();
//        $response->assertStatus(404);
        $response->assertStatus(200);
    }
}

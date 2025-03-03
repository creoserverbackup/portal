<?php

namespace Tests\Feature\Http\Controllers\Api;

use Tests\TestCase;

class FilterControllerTest extends TestCase
{


    public function test_index()
    {

        $response = $this->json(
            'post',
            '/api/filters',
            [
//                'attributes' => [['attribute_id' => 1, 'value' => ['ids' => [1, 2]]], ['attribute_id' => 13, 'value' => ['ids' => [21]]]]
//                'attributes' => [9 => ['ids' => [12]]]
//                'attributes' => [9 => ['ids' => [12, 17]],13 => ['ids' => [16, 21]]]


//                'a_32_ids' => [24],
//                'category_id' => 116
            ],
            [
                'webshop' => true
            ]
        );


        $response->dump();
        $response->assertStatus(200);
    }
}

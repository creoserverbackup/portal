<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        $this->assertTrue(true);
    }

    public function test_meilisearch()
    {
        $query = \App\Models\CatalogProduct::search('dell')->get();

        dump($query);

        $this->assertTrue(true);
    }
}

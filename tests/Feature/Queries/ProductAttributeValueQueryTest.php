<?php

namespace Tests\Feature\Queries;

use App\Services\Filter\Queries\ProductAttributeValueQuery;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductAttributeValueQueryTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        /** @var ProductAttributeValueQuery $actionQuery */
        $actionQuery = app(ProductAttributeValueQuery::class);
       $query = $actionQuery->query(['category_id' => 116]);

       $result = $query->get();

       $this->assertTrue($result->isNotEmpty());

    }
}

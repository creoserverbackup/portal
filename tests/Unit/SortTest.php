<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class SortTest extends TestCase
{
    public function test_usort()
    {
        $array = [
            (object)['name' => 'Albert', 'sort' => 0],
            (object)['name' => 'Den', 'sort' => 1],
            (object)['name' => 'Jon', 'sort' => 0],
            (object)['name' => 'Fred', 'sort' => 0],
            (object)['name' => 'Peter', 'sort' => 2],
        ];

        usort($array, function ($a, $b) {
            $rdiff = $a->sort <=> $b->sort;
            if ($rdiff) {
                return $rdiff;
            }
            return $a->name <=> $b->name;


/*            $rdiff = $a->name <=> $b->name;
            if ($rdiff) {
                return $rdiff;
            }
            return $a->sort <=> $b->sort;*/
        });


        dump($array);

        $this->assertTrue(true);
    }
}

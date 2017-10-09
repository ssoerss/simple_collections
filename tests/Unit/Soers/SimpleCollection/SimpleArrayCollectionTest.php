<?php

namespace tests\Unit\Soers\SimpleCollection;

use Soers\SimpleCollection\SimpleArrayCollection;
use PHPUnit\Framework\TestCase;

class SimpleArrayCollectionTest extends TestCase
{
    public function testInitializeEmpty()
    {
        $collection = new SimpleArrayCollection();
        $this->assertCount(0, $collection);
    }
}

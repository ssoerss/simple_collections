<?php declare(strict_types = 1);

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

    public function testInitializeWithAnArray()
    {
        $collection = new SimpleArrayCollection([10, 6, "12", new \stdClass()]);
        $this->assertCount(4, $collection);
    }

    public function testCollectionIsIterable()
    {
        $array = [1, 2, 5];
        $currentKey = 0;
        $collection = new SimpleArrayCollection($array);

        foreach($collection as $element) {
            $this->assertEquals($array[$currentKey++], $element);
        }
    }

    public function testCollectionCanBeMapped()
    {
        $elements = [2, 4, 6];
        $collection = new SimpleArrayCollection($elements);

        $mappedCollection = $collection->map(function($element) {
            return $element * 2;
        });

        foreach ($elements as $key => $number) {
            $this->assertEquals($number * 2, $mappedCollection->get($key));
        }
    }

    public function testCollectionCanBeFiltered()
    {
        $elements = [1, 3, 5];
        $collection = new SimpleArrayCollection($elements);

        $filteredCollection = $collection->filter(function($element) {
            if ($element === 3 || $element === 5) {

                return true;
            }

            return false;
        });

        $this->assertCount(2, $filteredCollection);
        $this->assertContains(3, $filteredCollection);
        $this->assertContains(5, $filteredCollection);
    }

    public function testCollectionCanBeIntersected()
    {
        $firstCollection = new SimpleArrayCollection([10, 20, 30]);
        $secondCollection = new SimpleArrayCollection([20, 30, 40, 50]);

        $intersection = $firstCollection->intersect($secondCollection);

        $this->assertCount(2, $intersection);
        $this->assertContains(20, $intersection);
        $this->assertContains(30, $intersection);
    }

    public function testCollectionCanBeDiffed()
    {
        $firstCollection = new SimpleArrayCollection([10, 20, 30]);
        $secondCollection = new SimpleArrayCollection([20, 30, 40, 50]);

        $diff = $firstCollection->diff($secondCollection);

        $this->assertCount(1, $diff);
        $this->assertContains(10, $diff);
    }

    public function testUniqueValuesCanBeTakenFromCollection()
    {
        $collection = new SimpleArrayCollection([10, 20, 30, 10, 30, 50]);

        $uniqueCollection = $collection->unique();

        $this->assertCount(4, $uniqueCollection);
        $this->assertContains(10, $uniqueCollection);
        $this->assertContains(20, $uniqueCollection);
        $this->assertContains(30, $uniqueCollection);
        $this->assertContains(50, $uniqueCollection);
    }

    public function testCollectionCanBeMerged()
    {
        $firstCollection = new SimpleArrayCollection([10, 20, 30]);
        $secondCollection = new SimpleArrayCollection([20, 30, 40, 50]);

        $mergedCollection = $firstCollection->merge($secondCollection);

        $this->assertCount(7, $mergedCollection);
        $this->assertEquals(10, $mergedCollection->get(0));
        $this->assertEquals(20, $mergedCollection->get(1));
        $this->assertEquals(30, $mergedCollection->get(2));
        $this->assertEquals(20, $mergedCollection->get(3));
        $this->assertEquals(30, $mergedCollection->get(4));
        $this->assertEquals(40, $mergedCollection->get(5));
        $this->assertEquals(50, $mergedCollection->get(6));
    }

    public function testCollectionCanBeFilteredByClass()
    {
        $className = \stdClass::class;
        $elements = [1, "23", null, new $className(), new $className(), new \Exception()];

        $collection = new SimpleArrayCollection($elements);
        $filteredCollection = $collection->filterByClass($className);

        $this->assertCount(2, $filteredCollection);
        $this->assertContainsOnlyInstancesOf($className, $filteredCollection);
    }

}

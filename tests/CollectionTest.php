<?php /** @noinspection PhpMethodParametersCountMismatchInspection */

/** @noinspection PhpUndefinedClassInspection */

namespace Foris\Easy\Support\Tests;

use ArrayIterator;
use Foris\Easy\Support\Collection;
use Foris\Easy\Support\Contracts\Arrayable;
use Foris\Easy\Support\Contracts\Jsonable;
use Mockery;

/**
 * Class CollectionTest
 */
class CollectionTest extends TestCase
{
    /**
     * test array access
     */
    public function testArrayAccess()
    {
        $collection = new Collection(['test_key' => 'test_value']);

        $this->assertTrue(isset($collection['test_key']));
        $this->assertSame('test_value', $collection['test_key']);

        unset($collection['test_key']);
        $this->assertFalse(isset($collection['test_key']));

        $collection['test_key_1'] = 'test_value_1';
        $this->assertTrue(isset($collection['test_key_1']));
        $this->assertSame('test_value_1', $collection['test_key_1']);
    }

    /**
     * test countable
     */
    public function testCountable()
    {
        $collection = new Collection(['test_key' => 'test_value']);
        $this->assertEquals(1, $collection->count());
    }

    /**
     * test iterator
     */
    public function testIterator()
    {
        $items = [
            'test_key' => 'test_value',
        ];
        $collection = new Collection($items);
        $this->assertInstanceOf(ArrayIterator::class, $collection->getIterator());

        foreach ($collection as $key => $value) {
            $this->assertSame($items[$key], $value);
        }
    }

    /**
     * test collection to json
     */
    public function testToJson()
    {
        $jsonable = Mockery::mock(Jsonable::class);
        $jsonable->shouldReceive('toJson')->andReturn(json_encode(['json' => 'json']));

        $arrayable = Mockery::mock(Arrayable::class);
        $arrayable->shouldReceive('toArray')->andReturn(['array' => 'array']);

        $collection = new Collection([
            'collection' => new Collection(['key' => 'value']),
            'jsonable' => $jsonable,
            'arrayable' => $arrayable,
        ]);

        $expectJson = json_encode(
            ['collection' => ['key' => 'value'], 'jsonable' => ['json' => 'json'], 'arrayable' => ['array' => 'array']]
        );
        $this->assertSame($expectJson, $collection->toJson());
    }

    /**
     * test collection to array
     */
    public function testToArray()
    {
        $collection = new Collection([
            'collection' => new Collection(['key' => 'value']),
        ]);
        $this->assertSame(['collection' => ['key' => 'value']], $collection->toArray());
    }

    /**
     * test get data from collection
     */
    public function testGetDataFromCollection()
    {
        $collection = new Collection([
            'collection' => new Collection(['key' => 'value']),
        ]);

        $this->assertSame('value', $collection->get('collection.key'));
        $this->assertNull($collection->get('not_exists_key'));
        $this->assertSame('default', $collection->get('not_exists_key', 'default'));
    }
}

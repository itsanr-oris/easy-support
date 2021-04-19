<?php /** @noinspection PhpUndefinedClassInspection */

namespace Foris\Easy\Support\Tests;

use ArrayAccess;
use Mockery;
use Foris\Easy\Support\Arr;

/**
 * Class ArrTest
 */
class ArrTest extends TestCase
{
    public function testCheckArrayAccessible()
    {
        $this->assertTrue(Arr::accessible([]));

        $arrayAccess = Mockery::mock(ArrayAccess::class);
        $this->assertTrue(Arr::accessible($arrayAccess));
    }

    public function testSetArrayItem()
    {
        $expected = [
            'key' => [
                'sub-key' => 'value'
            ],
        ];

        $actual = [];
        Arr::set($actual, 'key.sub-key', 'value');
        $this->assertEquals($expected, $actual);

        Arr::set($actual, null, $expected);
        $this->assertEquals($expected, $actual);
    }

    public function testGetArrayItem()
    {
        $array = [
            'key' => [
                'sub-key' => 'value'
            ],
        ];

        $this->assertEquals($array, Arr::get($array, null));
        $this->assertEquals('value', Arr::get($array, 'key.sub-key'));


        $default = function () {
            return 'default';
        };
        $this->assertNull(Arr::get($array, 'key.not-exists'));
        $this->assertEquals('default', Arr::get($array, 'key.not-exists', $default));
    }

    public function testArrayItemExists()
    {
        $array = [
            'key' => [
                'sub-key' => 'value'
            ],
        ];

        $this->assertTrue(Arr::exists($array, 'key.sub-key'));
        $this->assertFalse(Arr::exists($array, 'key.not-exists'));
    }

    public function testForgetArrayItem()
    {
        $expected = [
            'key' => [
                'sub-key-2' => 'value-2',
                'sub-key-3' => 'value-3',
            ],
        ];

        $actual = [
            'key' => [
                'sub-key-1' => 'value-1',
                'sub-key-2' => 'value-2',
                'sub-key-3' => 'value-3',
            ],
        ];

        Arr::forget($actual, 'key.sub-key-1');
        $this->assertEquals($expected, $actual);

        Arr::forget($actual, ['key.sub-key-2', 'key.sub-key-3']);
        $this->assertEquals(['key' => []], $actual);
    }

    public function testGetNewArrayWithOnlySpecifiedArrayItems()
    {
        $original = [
            'key-1' => [
                'sub-key-1' => 'value-1',
                'sub-key-2' => 'value-2',
            ],
            'key-2' => [
                'sub-key-1' => 'value-1',
                'sub-key-2' => 'value-2',
            ],
        ];

        $expected = [
            'key-1' => [
                'sub-key-1' => 'value-1',
            ],
            'key-2' => [
                'sub-key-2' => 'value-2',
            ]
        ];

        $this->assertEquals($expected, Arr::only($original, ['key-1.sub-key-1', 'key-2.sub-key-2']));
    }

    public function testGetNewArrayWithExceptSpecifiedArrayItems()
    {
        $original = [
            'key-1' => [
                'sub-key-1' => 'value-1',
                'sub-key-2' => 'value-2',
            ],
            'key-2' => [
                'sub-key-1' => 'value-1',
                'sub-key-2' => 'value-2',
            ],
        ];

        $expected = [
            'key-1' => [
                'sub-key-1' => 'value-1',
            ],
            'key-2' => [
                'sub-key-2' => 'value-2',
            ]
        ];

        $this->assertEquals($expected, Arr::except($original, ['key-1.sub-key-2', 'key-2.sub-key-1']));

        $this->assertThrowException(
            \PHPUnit_Framework_Error::class,
            'Deprecated: The expect() function is deprecated, use except() instead!'
        );
        Arr::expect($original, ['key-1.sub-key-2', 'key-2.sub-key-1']);
    }

    public function testFlattenAssociativeArrayIntoDot()
    {
        $original = [
            'key-1' => [
                'sub-key-1' => 'value-1',
                'sub-key-2' => 'value-2',
            ],
            'key-2' => [
                'sub-key-1' => 'value-1',
                'sub-key-2' => 'value-2',
            ],
        ];

        $expected = [
            'key-1.sub-key-1' => 'value-1',
            'key-1.sub-key-2' => 'value-2',
            'key-2.sub-key-1' => 'value-1',
            'key-2.sub-key-2' => 'value-2',
        ];

        $this->assertEquals($expected, Arr::dot($original));
    }

    public function testPluckArrayItemValues()
    {
        $original = [
            [
                'name' => 'name-1',
                'value' => 'value-1',
            ],
            [
                'name' => 'name-2',
                'value' => 'value-2',
            ]
        ];

        $expectedValues = [
            'value-1',
            'value-2'
        ];
        $this->assertEquals($expectedValues, Arr::pluck($original, 'value'));

        $expectedKeyValue = [
            'name-1' => 'value-1',
            'name-2' => 'value-2',
        ];

        $this->assertEquals($expectedKeyValue, Arr::pluck($original, 'value', 'name'));
    }

    public function testWrapAGivenValueIntoArray()
    {
        $this->assertEquals([], Arr::wrap(null));
        $this->assertEquals(['test'], Arr::wrap('test'));
        $this->assertEquals(['test'], Arr::wrap(['test']));
    }
}

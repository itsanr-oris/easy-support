<?php
/**
 * Created by PhpStorm.
 * User: f-oris
 * Date: 2019/8/16
 * Time: 2:57 PM
 */

namespace Foris\Easy\Support\Tests;

use ArrayAccess;
use Mockery;
use Foris\Easy\Support\Arr;
use PHPUnit\Framework\TestCase;

/**
 * Class ArrTest
 *
 * @package Foris\Easy\Support\Tests
 * @author  f-oris <us@f-oris.me>
 * @version 1.0.0
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

    public function testUnsetArrayItem()
    {
        $expected = [
            'key' => [],
        ];

        $actual = [
            'key' => [
                'sub-key' => 'value'
            ],
        ];

        Arr::unset($actual, 'key.sub-key');
        $this->assertEquals($expected, $actual);
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
            'key' => [],
        ];

        $actual = [
            'key' => [
                'sub-key-1' => 'value-1',
                'sub-key-2' => 'value-2',
            ],
        ];

        Arr::forget($actual, ['key.sub-key-1', 'key.sub-key-2']);
        $this->assertEquals($expected, $actual);
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

        $this->assertEquals($expected, Arr::expect($original, ['key-1.sub-key-2', 'key-2.sub-key-1']));
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
}
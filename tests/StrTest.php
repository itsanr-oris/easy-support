<?php

namespace Foris\Easy\Support\Tests;

use Foris\Easy\Support\Str;
use PHPUnit\Framework\TestCase;

/**
 * Class StrTest
 */
class StrTest extends TestCase
{
    /**
     * Test convert the given string to upper-case.
     */
    public function testGetUpperStr()
    {
        $this->assertEquals('ABC DEF', Str::upper('abc def'));
    }

    /**
     * Test convert the given string to lower-case.
     */
    public function testGetLowerStr()
    {
        $this->assertEquals('abc def', Str::lower('ABC DEF'));
    }

    /**
     * Test convert a value to studly caps case.
     */
    public function testGetStudlyStr()
    {
        $this->assertEquals('AbcDef', Str::studly('abc_def'));

        // test get studly str from cache
        $this->assertEquals('AbcDef', Str::studly('abc_def'));
    }

    /**
     * Test convert a value to camel case.
     */
    public function testGetCamelStr()
    {
        $this->assertEquals('abcDef', Str::camel('abc_def'));

        // test get camel str from cache
        $this->assertEquals('abcDef', Str::camel('abc_def'));
    }

    /**
     * Test convert a string to snake case.
     */
    public function testGetSnakeStr()
    {
        $this->assertEquals('abc_def', Str::snake('AbcDef'));

        // test get snake str from cache
        $this->assertEquals('abc_def', Str::snake('AbcDef'));
    }
}

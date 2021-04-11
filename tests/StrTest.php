<?php

namespace Foris\Easy\Support\Tests;

use Foris\Easy\Support\Str;

/**
 * Class StrTest
 */
class StrTest extends TestCase
{
    /**
     * Test Str::after()
     */
    public function testAfterFunction()
    {
        $this->assertEquals('Hello world', Str::after('Hello world', ''));
        $this->assertEquals(' world', Str::after('Hello world', 'Hello'));
    }

    /**
     * Test Str::before()
     */
    public function testBeforeFunction()
    {
        $this->assertEquals('Hello world', Str::before('Hello world', ''));
        $this->assertEquals('Hello ', Str::before('Hello world', 'world'));
    }

    /**
     * Test Str::camel()
     */
    public function testCamelFunction()
    {
        $this->assertEquals('abcDef', Str::camel('abc_def'));

        // test get camel str from cache
        $this->assertEquals('abcDef', Str::camel('abc_def'));
    }

    /**
     * Test Str::contains()
     */
    public function testContainsFunction()
    {
        $this->assertTrue(Str::contains('This is a test string.', ['test', 'hello world']));
        $this->assertFalse(Str::contains('This is a test string.', ['test 2', 'hello world']));
    }

    /**
     * Test Str::containsAll()
     */
    public function testContainsAllFunction()
    {
        $this->assertTrue(Str::containsAll('This is a test string.', ['test', 'string']));
        $this->assertFalse(Str::containsAll('This is a test string.', ['test', 'hello world']));
    }

    /**
     * Test Str::endsWith()
     */
    public function testEndsWithFunction()
    {
        $this->assertTrue(Str::endsWith('This is a test string.', 'string.'));
        $this->assertFalse(Str::endsWith('This is a test string.', 'string'));
    }

    /**
     * Test Str::finish()
     */
    public function testFinishFunction()
    {
        $this->assertEquals('/aaa/bbb/aaa', Str::finish('/aaa/bbb', '/aaa'));
    }

    /**
     * Test Str::is()
     */
    public function testIsFunction()
    {
        $this->assertTrue(Str::is('foobar', 'foobar'));
        $this->assertTrue(Str::is('foo*', 'foobar'));
        $this->assertFalse(Str::is('baz*', 'foobar'));
        $this->assertTrue(Str::is(['foo*', 'baz*'], 'foobar'));
        $this->assertFalse(Str::is(null, 'foobar'));
    }

    /**
     * Test Str::kebab()
     */
    public function testKebabFunction()
    {
        $this->assertEquals('abc-def', Str::kebab('abcDef'));
    }

    /**
     * Test Str::length()
     */
    public function testLengthFunction()
    {
        $string = 'Hi，好久不见！';
        $this->assertEquals(mb_strlen($string), Str::length($string));
        $this->assertEquals(mb_strlen($string, 'UTF-8'), Str::length($string, 'UTF-8'));
        $this->assertEquals(mb_strlen($string, 'gbk'), Str::length($string, 'gbk'));
    }

    /**
     * Test Str::limit()
     */
    public function testLimitFunction()
    {
        $this->assertEquals('Hello', Str::limit('Hello', 5));
        $this->assertEquals('Hello...', Str::limit('Hello world!', 5));
    }

    /**
     * Test Str::lower()
     */
    public function testLowerFunction()
    {
        $this->assertEquals('abc def', Str::lower('ABC DEF'));
    }

    /**
     * Test Str::words()
     */
    public function testWordsFunction()
    {
        $this->assertEquals('Hello world!', Str::words('Hello world!', 3));
        $this->assertEquals('Hello...', Str::words('Hello world!', 1));
    }

    /**
     * Test Str::random()
     *
     * @throws \Exception
     */
    public function testRandomFunction()
    {
        $this->assertEquals(24, strlen(Str::random(24)));
    }

    /**
     * Test Str::replaceArray()
     */
    public function testReplaceArrayFunction()
    {
        $string = 'The event will take place between :time and :time';
        $expect = 'The event will take place between 8:30 and 9:30';

        $this->assertEquals($expect, Str::replaceArray(':time', ['8:30', '9:30'], $string));
    }

    /**
     * Test Str::replaceFirst()
     */
    public function testReplaceFirstFunction()
    {
        $string = 'The event will take place between :time and :time';
        $expect = 'The event will take place between 8:30 and :time';

        $this->assertEquals($expect, Str::replaceFirst(':time', '8:30', $string));
        $this->assertEquals($string, Str::replaceFirst('', '', $string));
        $this->assertEquals($string, Str::replaceFirst('non exist substr', 'substr', $string));
    }

    /**
     * Test Str::replaceLast()
     */
    public function testReplaceLastFunction()
    {
        $string = 'The event will take place between :time and :time';
        $expect = 'The event will take place between :time and 9:30';

        $this->assertEquals($expect, Str::replaceLast(':time', '9:30', $string));
        $this->assertEquals($string, Str::replaceLast('', '', $string));
        $this->assertEquals($string, Str::replaceLast('non exist substr', 'substr', $string));
    }

    /**
     * Test Str::start()
     */
    public function testStartFunction()
    {
        $this->assertEquals('Hello world', Str::start('world', 'Hello '));
    }

    /**
     * Test Str::upper()
     */
    public function testUpperFunction()
    {
        $this->assertEquals('ABC DEF', Str::upper('abc def'));
    }

    /**
     * Test Str::snake()
     */
    public function testSnakeFunction()
    {
        $this->assertEquals('abc_def', Str::snake('AbcDef'));

        // test get snake str from cache
        $this->assertEquals('abc_def', Str::snake('AbcDef'));
    }

    /**
     * Test Str::startsWith()
     */
    public function testStartWithFunction()
    {
        $this->assertTrue(Str::startsWith('Hello world', 'Hello'));
        $this->assertFalse(Str::startsWith('Hello world', 'Hi'));
        $this->assertTrue(Str::startsWith('Hello world', ['Hello', 'Hi']));
    }

    /**
     * Test Str::studly()
     */
    public function testStudlyFunction()
    {
        $this->assertEquals('AbcDef', Str::studly('abc_def'));

        // test get studly str from cache
        $this->assertEquals('AbcDef', Str::studly('abc_def'));
    }
}

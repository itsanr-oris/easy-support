<?php

namespace Foris\Easy\Support\Tests;

use Foris\Easy\Support\Util;
use PHPUnit\Framework\TestCase;

/**
 * Class UtilTest
 */
class UtilTest extends TestCase
{
    /**
     * test value function
     */
    public function testValue()
    {
        $callback = function () {
            return 'expected';
        };
        $this->assertEquals('expected', Util::value($callback));

        $this->assertEquals('expected', Util::value('expected'));
    }
}

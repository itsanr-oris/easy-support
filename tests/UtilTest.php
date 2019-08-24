<?php
/**
 * Created by PhpStorm.
 * User: f-oris
 * Date: 2019/8/16
 * Time: 2:58 PM
 */

namespace Foris\Easy\Support\Tests;

use Foris\Easy\Support\Util;
use PHPUnit\Framework\TestCase;

/**
 * Class UtilTest
 *
 * @package Foris\Easy\Support\Tests
 * @author  f-oris <us@f-oris.me>
 * @version 1.0.0
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
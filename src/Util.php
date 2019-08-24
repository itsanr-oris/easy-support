<?php

namespace Foris\Easy\Support;

/**
 * Class Util
 */
class Util
{
    /**
     * Return the real value of the given value.
     *
     * @param $value
     * @return mixed
     */
    public static function value($value)
    {
        return is_callable($value) ? $value() : $value;
    }
}
<?php

namespace Foris\Easy\Support;

use ArrayAccess;
use Exception;

/**
 * Array helper base on Illuminate\Support\Arr.
 *
 * @method static unset(&$array, $key)
 * @method static expect($array, $keys)
 */
class Arr
{
    /**
     * Determine whether the given value is array accessible.
     *
     * @param $value
     * @return bool
     */
    public static function accessible($value)
    {
        return is_array($value) || $value instanceof ArrayAccess;
    }

    /**
     * Set an array item to a given value using "dot" notation.
     *
     * If no key is given to the method, the entire array will be replaced.
     *
     * @param  array   $array
     * @param  string  $key
     * @param  mixed   $value
     * @return array
     */
    public static function set(&$array, $key, $value)
    {
        if (is_null($key)) {
            return $array = $value;
        }

        $keys = explode('.', $key);

        while (count($keys) > 1) {
            $key = array_shift($keys);

            // If the key doesn't exist at this depth, we will just create an empty array
            // to hold the next value, allowing us to create the arrays to hold final
            // values at the correct depth. Then we'll keep digging into the array.
            if (! isset($array[$key]) || ! is_array($array[$key])) {
                $array[$key] = [];
            }

            $array = &$array[$key];
        }

        $array[array_shift($keys)] = $value;

        return $array;
    }

    /**
     * Get an item from an array using "dot" notation.
     *
     * @param  \ArrayAccess|array  $array
     * @param  string|int  $key
     * @param  mixed   $default
     * @return mixed
     */
    public static function get($array, $key, $default = null)
    {
        if (is_null($key)) {
            return $array;
        }

        foreach (explode('.', $key) as $segment) {
            if (static::accessible($array) && isset($array[$segment])) {
                $array = $array[$segment];
            } else {
                return Util::value($default);
            }
        }

        return $array;
    }

    /**
     * Determine if the given key exists in the provided array.
     *
     * @param $array
     * @param $key
     * @return bool
     */
    public static function exists($array, $key)
    {
        $keys = explode('.', $key);

        while (count($keys) > 1) {
            $key = array_shift($keys);

            if (isset($array[$key]) && is_array($array[$key])) {
                $array = $array[$key];
            }
        }

        return isset($array[array_shift($keys)]);
    }

    /**
     * Remove one or many array items from a given array using "dot" notation.
     *
     * @param $array
     * @param $keys
     */
    public static function forget(&$array, $keys)
    {
        foreach (static::wrap($keys) as $key) {
            self::forgetItem($array, $key);
        }
    }

    /**
     * Remove item from a given array using "dot" notation.
     *
     * @param $array
     * @param $key
     */
    protected static function forgetItem(&$array, $key)
    {
        $keys = explode('.', $key);

        while (count($keys) > 1) {
            $key = array_shift($keys);

            if (isset($array[$key]) && is_array($array[$key])) {
                $array = &$array[$key];
            }
        }

        unset($array[array_shift($keys)]);
    }

    /**
     * Get a subset of the items from the given array.
     *
     * @param  array        $array
     * @param  array|string $keys
     * @return array
     */
    public static function only($array, $keys)
    {
        $result = [];

        foreach ($keys as $key) {
            $value = static::get($array, $key);
            if ($value !== null) {
                static::set($result, $key, $value);
            }
        }

        return $result;
    }

    /**
     * Flatten a multi-dimensional associative array with dots.
     *
     * @param  array  $array
     * @param  string $prepend
     * @return array
     */
    public static function dot($array, $prepend = '')
    {
        $results = [];

        foreach ($array as $key => $value) {
            if (is_array($value) && !empty($value)) {
                $results = array_merge($results, static::dot($value, $prepend . $key . '.'));
            } else {
                $results[$prepend . $key] = $value;
            }
        }

        return $results;
    }

    /**
     * Pluck an array of values from an array.
     *
     * @param      $array
     * @param      $value
     * @param null $key
     * @return array
     */
    public static function pluck($array, $value, $key = null)
    {
        $result = [];

        foreach ($array as $item) {
            if (is_null($key)) {
                $result[] = static::get($item, $value);
            } else {
                $result[static::get($item, $key)] = static::get($item, $value);
            }
        }

        return $result;
    }

    /**
     * Get all of the given array except for a specified array of keys.
     *
     * @param  array  $array
     * @param  array|string  $keys
     * @return array
     */
    public static function except($array, $keys)
    {
        static::forget($array, $keys);
        return $array;
    }

    /**
     * If the given value is not an array and not null, wrap it in one.
     *
     * @param  mixed  $value
     * @return array
     */
    public static function wrap($value)
    {
        if (is_null($value)) {
            return [];
        }

        return is_array($value) ? $value : [$value];
    }

    /**
     * Fix static::unset() method.
     *
     * @param $name
     * @param $arguments
     * @throws Exception
     * @codeCoverageIgnore
     */
    public static function __callStatic($name, $arguments)
    {
        if ($name == 'unset') {
            trigger_error('Deprecated: The unset() function is deprecated, use unsetItem() instead!', E_USER_NOTICE);
        }

        if ($name == 'expect') {
            trigger_error('Deprecated: The expect() function is deprecated, use except() instead!', E_USER_NOTICE);
        }

        trigger_error("Error: Call to undefined method Foris\Easy\Support\Arr::{$name}()!", E_USER_ERROR);
    }
}

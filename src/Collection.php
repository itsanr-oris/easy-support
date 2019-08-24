<?php

namespace Foris\Easy\Support;

use ArrayAccess;
use ArrayIterator;
use Countable;
use Foris\Easy\Support\Contracts\Arrayable;
use Foris\Easy\Support\Contracts\Jsonable;
use IteratorAggregate;
use JsonSerializable;

/**
 * Collection base on Illuminate\Support\Collection.
 */
class Collection implements ArrayAccess, Countable, IteratorAggregate, Jsonable, JsonSerializable, Arrayable
{
    /**
     * @var array
     */
    protected $items = [];

    /**
     * Collection constructor.
     *
     * @param array $items
     */
    public function __construct(array $items = [])
    {
        $this->items = $items;
    }

    /**
     * Determine if an item exists at an offset.
     *
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->items);
    }

    /**
     * Get an item at a given offset.
     *
     * @param mixed $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->items[$offset];
    }

    /**
     * Set the item at a given offset.
     *
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        $this->items[$offset] = $value;
    }

    /**
     * Unset the item at a given offset.
     *
     * @param mixed $offset
     */
    public function offsetUnset($offset)
    {
        unset($this->items[$offset]);
    }

    /**
     * Count the number of items in the collection.
     *
     * @return int
     */
    public function count()
    {
        return count($this->items);
    }

    /**
     * Get an iterator for the items.
     *
     * @return ArrayIterator|\Traversable
     */
    public function getIterator()
    {
        return new ArrayIterator($this->items);
    }

    /**
     * Get the collection of items as JSON.
     *
     * @param int $options
     * @return false|string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->jsonSerialize(), $options);
    }

    /**
     * Convert the object into something JSON serializable.
     *
     * @return array|mixed
     */
    public function jsonSerialize()
    {
        $callable = function ($value) {
            if ($value instanceof JsonSerializable) {
                return $value->jsonSerialize();
            }

            if ($value instanceof Jsonable) {
                return json_decode($value->toJson(), true);
            }

            if (is_object($value) && method_exists($value, 'toArray')) {
                return $value->toArray();
            }

            return $value;
        };

        return array_map($callable, $this->items);
    }

    /**
     * Get the collection of items as a plain array.
     *
     * @return array
     */
    public function toArray()
    {
        return array_map(function ($value) {
            return $value instanceof Arrayable ? $value->toArray() : $value;
        }, $this->items);
    }

    /**
     * Get an item from the collection by key.
     *
     * @param      $key
     * @param null $default
     * @return array|mixed|null
     */
    public function get($key, $default = null)
    {
        return Arr::get($this->items, $key, $default);
    }
}
<?php

namespace PandaCore\Core\Support;

use ArrayAccess;
use Countable;
use IteratorAggregate;
use ArrayIterator;
use JsonSerializable;

class Collection implements ArrayAccess, Countable, IteratorAggregate, JsonSerializable
{
    protected array $items = [];

    public function __construct($items = [])
    {
        if (is_object($items) && !is_array($items)) {
            $this->items = [$items];
        } elseif (is_array($items)) {
            $this->items = $items;
        } else {
            $this->items = [];
        }
    }



    // Add an item to the collection.
    public function add($item): self
    {
        $this->items[] = $item;
        return $this;
    }

    // Return all items.
    public function all(): array
    {
        return $this->items;
    }

    // Filter items in a collection.
    public function filter(callable $callback): self
    {
        return new static(array_filter($this->items, $callback));
    }

    // Convert each item to an array (toArray).
    public function toArray(): array
    {
        return array_map(function ($item) {
            if (is_object($item) && method_exists($item, 'toArray')) {
                return $item->toArray();
            }
            return $item;
        }, $this->items);
    }

    // Loop through each item.
    public function map(callable $callback): self
    {
        return new static(array_map($callback, $this->items));
    }

    // Check the number of elements.
    public function count(): int
    {
        return count($this->items);
    }

    // ArrayAccess implementation
    public function offsetExists($offset): bool
    {
        return isset($this->items[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->items[$offset] ?? null;
    }

    public function offsetSet($offset, $value): void
    {
        if ($offset === null) {
            $this->items[] = $value;
        } else {
            $this->items[$offset] = $value;
        }
    }

    public function offsetUnset($offset): void
    {
        unset($this->items[$offset]);
    }

    // IteratorAggregate implementation
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->items);
    }

    // JsonSerializable implementation
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}

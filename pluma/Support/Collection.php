<?php

// ==========================================
// File: pluma/Support/Collection.php
// Description: Fluent wrapper around arrays for chainable operations
// ==========================================

namespace Pluma\Support;

class Collection implements \IteratorAggregate
{
    /**
     * The underlying items array.
     *
     * @var array
     */
    protected array $items;

    /**
     * Create a new collection instance.
     *
     * @param array $items
     */
    public function __construct(array $items = [])
    {
        $this->items = $items;
    }

    /**
     * Map each item using the callback.
     *
     * @param callable $callback
     * @return static
     */
    public function map(callable $callback): static
    {
        return new static(array_map($callback, $this->items));
    }

    /**
     * Filter items using the callback.
     *
     * @param callable $callback
     * @return static
     */
    public function filter(callable $callback): static
    {
        return new static(array_filter($this->items, $callback, ARRAY_FILTER_USE_BOTH));
    }

    /**
     * Return all items as an array.
     *
     * @return array
     */
    public function all(): array
    {
        return $this->items;
    }

    /**
     * Get an iterator for foreach support.
     *
     * @return \ArrayIterator
     */
    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->items);
    }
}
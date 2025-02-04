<?php

declare(strict_types=1);

namespace Andsudev\Easyrepo\DataEntry;

use ArrayAccess;
use Countable;
use IteratorAggregate;
use Traversable;

abstract class AbstractDataEntry implements ArrayAccess, Countable, IteratorAggregate
{
    protected ArrayAccess|iterable $data = [];

    public function __construct(ArrayAccess|array $data) {
        $this->data = $this->hydrate($data);
    }

    public function offsetExists($offset): bool
    {
        return isset($this->data[$offset]);
    }

    public function offsetGet($offset): mixed
    {
        return $this->data[$offset];
    }

    public function offsetSet($offset, $value): void

    {
        $this->data[$offset] = $value;
    }

    public function offsetUnset($offset): void
    {
        unset($this->data[$offset]);
    }

    public function count(): int
    {
       $hasCountMethod = is_object($this->data) && method_exists($this->data, 'count');

        if ($hasCountMethod) {
            return $this->data->count();
        }

        if ($this->data instanceof Countable) {
            return count($this->data);
        }

        $isEmpty = empty($this->data);
        if (!$isEmpty) {
            return 1;
        }

        return 0;
    }

    public function getIterator(): Traversable
    {
        $canIterate = $this->data instanceof IteratorAggregate;
        if ($canIterate) {
            return $this->data->getIterator();
        }

        return new \ArrayIterator($this->data);
    }

    public function __get($name)
    {
        if ($this->offsetExists($name)) {
            return $this->offsetGet($name);
        }

        return $this->data->$name;
    }

    abstract protected function hydrate(ArrayAccess|array $data): ArrayAccess|array;
}

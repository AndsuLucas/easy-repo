<?php

declare(strict_types=1);

namespace Andsudev\Easyrepo\DataEntry;

use ArrayAccess;

abstract class AbstractDataEntry implements ArrayAccess
{
    protected ArrayAccess|iterable $data;

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

    abstract protected function hydrate(ArrayAccess|array $data): ArrayAccess|array;
}

<?php

declare(strict_types=1);

namespace Andsudev\Easyrepo\DataEntry;

use Andsudev\Easyrepo\DataEntry\AbstractDataEntry;
use ArrayAccess;

class EasyRepoDataEntry extends AbstractDataEntry
{
    protected function hydrate(ArrayAccess|array $data): ArrayAccess|array
    {
        return $data;
    }
}
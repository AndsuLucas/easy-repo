<?php

declare(strict_types=1);

namespace Andsudev\Easyrepo\Repository;

use Andsudev\Easyrepo\DataEntry\AbstractDataEntry;

interface EasyRepositoryInterface
{
    public function create(array $data): mixed;
    public function find(int|string $id): ?AbstractDataEntry;
    public function findBy(array $criteria): ?AbstractDataEntry;
    public function getAll(): AbstractDataEntry;
    public function getBy(array $criteria): AbstractDataEntry;
    public function update(int|string $id, array $data): bool;
    public function delete(int|string $id): bool;
}

<?php

declare(strict_types=1);

namespace Andsudev\Easyrepo\Repository;

use Andsudev\Easyrepo\DataEntry\AbstractDataEntry;

interface EasyRepositoryInterface
{
    public function create(array $data): bool;
    public function find(int|string $id): ?AbstractDataEntry;
    public function findBy(array $criteria): ?AbstractDataEntry;
    public function update(int|string $id, array $data): bool;
    public function delete(int|string $id): bool;
    public function limit(int $limit): self;
    public function columns(array $columns): self;
    public function orderBy(array $orderBy): self;
}

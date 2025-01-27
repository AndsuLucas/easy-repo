<?php

declare(strict_types=1);

namespace Andsudev\Easyrepo\Repository;

use Andsudev\Easyrepo\Repository\EasyRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Andsudev\Easyrepo\DataEntry\EasyRepoDataEntry;
use Andsudev\Easyrepo\DataEntry\AbstractDataEntry;
use ArrayAccess;

abstract class ElouquentRepository implements EasyRepositoryInterface
{
    protected string $modelClass;

    protected Model $model;

    public function __construct()
    {
        $this->mountModel();
    }

    protected function mountModel()
    {
        if (!class_exists($this->modelClass)) {
            throw new \Exception("Model class not found");
        }

        $this->model = new $this->modelClass;
    }

    /**
     * Create a new data entry.
     */
    public function create(array $data): mixed
    {
        return $this->model->create($data);
    }

    /**
     * Return a single data entry based on the id.
     */
    public function find(int|string $id): ?AbstractDataEntry
    {
        $registry = $this->model->find($id);
        return $this->mountDataEntry($registry);
    }

    public function findBy(array $criteria): ?AbstractDataEntry
    {
        $registry = $this->model->where(...$criteria)->first();
        return $this->mountDataEntry($registry);
    }

    public function getAll(): AbstractDataEntry
    {
        return new EasyRepoDataEntry([]);
    }

    public function getBy(array $criteria): AbstractDataEntry
    {
        return new EasyRepoDataEntry([]);
    }

    public function update(int|string $id, array $data): bool
    {
        return true;
    }

    public function delete(int|string $id): bool
    {
        return true;
    }

    /**
     * Mount the data entry.
     * @todo Add a way to get dynamic data entry
     * @link <issue-link>
     */
    protected function mountDataEntry(mixed $data): ?AbstractDataEntry
    {
        $isIterable = is_iterable($data);
        $implementsArrayAccess = $data instanceof \ArrayAccess;

        $needsToConvertToArray = !$isIterable && !$implementsArrayAccess;
        if ($needsToConvertToArray) {
            $hasToArrayMethod = is_object($data)
                && method_exists($data, 'toArray');

            return  $hasToArrayMethod ?
                new EasyRepoDataEntry($data->toArray())
                : null;
        }

        return new EasyRepoDataEntry($data);
    }
}

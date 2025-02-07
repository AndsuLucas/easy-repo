<?php

declare(strict_types=1);

namespace Andsudev\Easyrepo\Repository;

use Andsudev\Easyrepo\Repository\EasyRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Andsudev\Easyrepo\DataEntry\EasyRepoDataEntry;
use Andsudev\Easyrepo\DataEntry\AbstractDataEntry;
use Andsudev\Easyrepo\Repository\Clauses\Clauses;
use Illuminate\Database\Eloquent\Builder;

/**
 * @template T of Model
 */
abstract class EloquentRepository implements EasyRepositoryInterface
{

    use Clauses;

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
    public function create(array $data): bool
    {
        return (bool)$this->model->create($data);
    }

    /**
     * Return a single data entry based on the id.
     * @return AbstractDataEntry<T>
     */
    public function find(int|string $id): ?AbstractDataEntry
    {
        $registry = $this->model->find($id, $this->columns);
        return $this->mountDataEntry($registry);
    }

    /**
     * Return data entry based on the criteria.
     */
    public function findBy(array $criteria, ...$additionalCriteria): ?AbstractDataEntry
    {
        /** @var Builder $query */
        $query = $this->model->where(...$criteria);

        $hasAdditionalCriteria = count($additionalCriteria) > 0;
        if (!$hasAdditionalCriteria) {
            $query = $this->mountClauses($query);
            return $this->mountDataEntry($query->get());
        }

        $query = $this->mountClauses($query);
        $query = $this->mountAdditionalSearchClauses($query, $additionalCriteria);
        $registry = $query->get();
        return $this->mountDataEntry($registry);
    }

    public function update(int|string $id, array $data): bool
    {
        return (bool)$this->model->find($id)->update($data);
    }

    public function delete(int|string $id): bool
    {
        return (bool)$this->model->destroy($id);
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
        if (!$needsToConvertToArray) {
            return new EasyRepoDataEntry($data);
        }

        $hasToArrayMethod = is_object($data)
            && method_exists($data, 'toArray');

        return  $hasToArrayMethod ?
            new EasyRepoDataEntry($data->toArray())
            : null;
    }
}

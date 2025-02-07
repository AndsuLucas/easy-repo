<?php

declare(strict_types=1);

namespace Andsudev\Easyrepo\Repository\Clauses;

use Illuminate\Database\Eloquent\Builder;

trait Clauses
{
    protected ?int $limit = null;

    protected array $columns = ['*'];

    protected array $orderBy = [];

    public function limit(int $limit): self
    {
        $this->limit = $limit;
        return $this;
    }

    public function columns(array $columns): self
    {
        $this->columns = $columns;
        return $this;
    }

    public function orderBy(array $orderBy): self
    {
        $this->orderBy = $orderBy;
        if (count($this->orderBy) != 2) {
            throw new \Exception("Invalid order by clause. Try ['column', 'asc|desc']");
        }

        return $this;
    }

    protected function mountAdditionalSearchClauses(Builder $query, array $criteria): Builder
    {
        foreach ($criteria as $crit) {
            if (!is_array($crit) || count($crit) !== 4) {
                throw new \Exception("Invalid criteria: " . var_export($crit, true));
            }

            [$clause, $column, $operator, $value] = $crit;

            if (!in_array($clause, ['and', 'or'])) {
                throw new \Exception("Invalid clause: " . $clause);
            }

            $clauseMethod = $clause == 'or' ? 'orWhere' : 'where';
            /** @var Builder $query */
            $query = $query->$clauseMethod($column, $operator, $value);
        }

        return $query;
    }


    protected function mountClauses(Builder $query): Builder
    {
        if ($this->limit) {
            $query->limit($this->limit);
        }

        if (count($this->orderBy) == 2) {
            [$order, $direction] = $this->orderBy;

            $query = $direction == 'desc'
                ? $query->orderByDesc($order)
                : $query->orderBy($order);
        }

        if ($this->columns) {
            $query->select($this->columns);
        }

        $this->limit = null;
        $this->orderBy = [];
        $this->columns = ['*'];

        return $query;
    }
}

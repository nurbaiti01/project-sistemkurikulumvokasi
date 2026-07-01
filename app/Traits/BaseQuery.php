<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait BaseQuery
{
    protected function applySearch(Builder $query, array $searchable): Builder
    {
        if (!$this->search) {
            return $query;
        }

        return $query->where(function ($q) use ($searchable) {
            foreach ($searchable as $item) {

                // 1️⃣ Simple column search
                if (is_string($item)) {
                    $q->orWhere($item, 'like', "%{$this->search}%");
                    continue;
                }

                // 2️⃣ Complex relation search
                $type = $item['type'] ?? 'column';

                // relation : whereHas
                if ($type === 'relation') {
                    $relation = $item['relation'];
                    $column = $item['column'] ?? $relation . '.id';

                    $q->orWhereHas(
                        $relation,
                        fn($rel) =>
                        $rel->where($column, 'like', "%{$this->search}%")
                    );
                    continue;
                }

                // pivot search
                if ($type === 'pivot') {
                    $relation = $item['relation'];
                    $pivotKey = $item['pivot'] ?? 'id';

                    $q->orWhereHas(
                        $relation,
                        fn($rel) =>
                        $rel->wherePivot($pivotKey, 'like', "%{$this->search}%")
                    );
                }
            }
        });
    }


    protected function applyFilters(Builder $query, array $filterable): Builder
    {
        foreach ($this->filter as $key => $value) {

            if ($value === null || !isset($filterable[$key])) {
                continue;
            }

            // Pastikan value menjadi array (meskipun input single value)
            $values = is_array($value) ? $value : [$value];

            $config = $filterable[$key];
            $type = $config['type'] ?? 'column';

            // 1️⃣ Column filter — direct whereIn
            if ($type === 'column') {
                $column = $config['column'] ?? $key;
                $query->whereIn($column, $values);
                continue;
            }

            // 2️⃣ Relation filter — whereHas + whereIn
            if ($type === 'relation') {
                $relation = $config['relation'];
                $column = $config['column'] ?? $relation . '.id';

                $query->whereHas($relation, function ($q) use ($column, $values) {
                    $q->whereIn($column, $values);
                });
                continue;
            }

            // 3️⃣ Pivot many-to-many filter — wherePivot + whereIn
            if ($type === 'pivot') {
                $relation = $config['relation'];
                $pivotKey = $config['pivot'] ?? $key;

                $query->whereHas($relation, function ($q) use ($pivotKey, $values) {
                    $q->wherePivotIn($pivotKey, $values);
                });
                continue;
            }
        }

        return $query;
    }



    protected function applyFilterProdi(Builder $query, string $column, array $relation = []): Builder
    {
        // dd($column,$relation);
        if (!$this->activeProdi) {
            return $query;
        }

        // If no relation → direct condition
        // if (empty($relation)) {
        //     return $query->where($column, $this->activeProdi);
        // }

        // Apply nested whereHas

        $query->whereHas(array_shift($relation), function ($q) use ($relation) {
            $this->applyNestedRelationFilter($q, $relation);
        });

        return $query;
    }

    protected function applyNestedRelationFilter($q, array $relation)
    {
        if (!empty($relation)) {
            $q->whereHas(array_shift($relation), function ($sub) use ($relation) {
                $this->applyNestedRelationFilter($sub, $relation);
            });
            return;
        }

        // Final level: auto detect foreign column name using convention:
        // {relation_name}.id OR custom from property
        $lastRelation = $q->getModel()->getTable(); // detects related model table

        $foreignKeyColumn = $this->filterProdiColumn
            ?? $lastRelation . '.id';  // fallback: {table}.id

        $q->where($foreignKeyColumn, $this->activeProdi);
    }

    protected function applySorting(Builder $query): Builder
    {
        if (!in_array($this->sortBy, $this->allowedSorts))
            return $query;
        if (!in_array($this->sortDirection, $this->allowedDirections))
            return $query;

        return $query->orderBy($this->sortBy, $this->sortDirection);
    }

    protected function autoQuery(Builder $builder, array $searchble, array $filterable): Builder
    {
        return $builder
            // ->tap(fn($q) => $this->applyFilterProdi($q, $prodiColumn, $relations))
            ->tap(fn($q) => $this->applyFilters($q, $filterable))
            ->tap(fn($q) => $this->applySearch($q, $searchble))
            ->tap(fn($q) => $this->applySorting($q));
    }
}

<?php

namespace App\Queries\Product\Sorts;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Sorts\Sort;

class BrandName implements Sort
{
    public function __invoke(Builder $query, bool $descending, string $property)
    {
        $query->orderBy('brands.name', $descending ? 'desc' : 'asc');
    }
}

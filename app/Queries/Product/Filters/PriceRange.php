<?php

namespace App\Queries\Product\Filters;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class PriceRange implements Filter
{
    public function __invoke(Builder $query, $value, string $property)
    {
        $low = intval(explode('-', $value)[0] * 100);
        $high = intval(explode('-', $value)[1] * 100);

        $query->whereBetween('price', [$low, $high]);
    }
}

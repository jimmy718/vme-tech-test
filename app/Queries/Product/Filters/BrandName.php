<?php

namespace App\Queries\Product\Filters;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class BrandName implements Filter
{
    public function __invoke(Builder $query, $value, string $property)
    {
        $query->whereHas('brand', function (Builder $query) use ($value) {
            $query->where('brands.name', 'like', '%' . $value . '%');
        });
    }
}

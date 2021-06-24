<?php

namespace App\Queries\Product\Sorts;

use App\Models\Brand;
use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Sorts\Sort;

class BrandName implements Sort
{
    public function __invoke(Builder $query, bool $descending, string $property)
    {
        $query->orderBy(
            Brand::select('name')
                ->whereColumn('id', 'products.brand_id')
                ->limit(1),
            $descending ? 'desc' : 'asc'
        );
    }
}

<?php

namespace App\Queries\Product;

use App\Models\Product;
use App\Queries\Product\Filters\PriceRange;
use App\Queries\Product\Filters\SearchProductNameBarcodeAndBrand;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class PaginatedProductsQuery
{
    public static function run(int $perPage, array $linkAppends): LengthAwarePaginator
    {
        return QueryBuilder::for(Product::class)
            ->allowedSorts('name', 'barcode', 'brand', 'price', 'date_added')
            ->allowedFilters([
                'brand',
                AllowedFilter::custom('search', new SearchProductNameBarcodeAndBrand()),
                AllowedFilter::custom('price-range', new PriceRange())
            ])
            ->paginate($perPage)
            ->appends($linkAppends);
    }
}

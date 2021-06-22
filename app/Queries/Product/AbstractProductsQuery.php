<?php

namespace App\Queries\Product;

use App\Models\Product;
use App\Queries\Product\Filters\PriceRange;
use App\Queries\Product\Filters\SearchProductNameBarcodeAndBrand;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

abstract class AbstractProductsQuery
{
    public function base(): QueryBuilder
    {
        return QueryBuilder::for(Product::class)
            ->allowedSorts('name', 'barcode', 'brand', 'price', 'date_added')
            ->allowedFilters([
                'brand',
                AllowedFilter::custom('search', new SearchProductNameBarcodeAndBrand()),
                AllowedFilter::custom('price-range', new PriceRange())
            ])
    }
}

<?php

namespace App\Queries\Product;

use App\Models\Product;
use App\Queries\Product\Filters\BrandName as BrandNameFilter;
use App\Queries\Product\Filters\PriceRange;
use App\Queries\Product\Filters\SearchProductNameBarcodeAndBrand;
use App\Queries\Product\Sorts\BrandName as BrandNameSort;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

abstract class AbstractProductsQuery
{
    protected function baseQuery(): QueryBuilder
    {
        return QueryBuilder::for(Product::class)
            ->with([
                'brand'
            ])
            ->allowedSorts([
                'name',
                'barcode',
                'price',
                'date_added',
                AllowedSort::custom('brand', new BrandNameSort()),
            ])
            ->allowedFilters([
                AllowedFilter::custom('brand', new BrandNameFilter()),
                AllowedFilter::custom('price-range', new PriceRange()),
                AllowedFilter::custom('search', new SearchProductNameBarcodeAndBrand()),
            ]);
    }
}

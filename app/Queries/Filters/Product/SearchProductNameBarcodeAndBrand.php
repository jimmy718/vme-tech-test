<?php

namespace App\Queries\Filters\Product;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class SearchProductNameBarcodeAndBrand implements Filter
{
    public function __invoke(Builder $query, $value, string $property)
    {
        $query->where(function (Builder $query) use ($value) {
            $query
                ->where('name', 'like', $this->preparedValue($value))
                ->orWhere('brand', 'like', $this->preparedValue($value))
                ->orWhere('barcode', 'like', $this->preparedValue($value));
        });
    }

    protected function preparedValue($value): string
    {
        return '%' . $value . '%';
    }
}

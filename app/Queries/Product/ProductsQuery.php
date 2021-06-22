<?php

namespace App\Queries\Product;

use Illuminate\Database\Eloquent\Collection;

class ProductsQuery extends AbstractProductsQuery
{
    public function run(): Collection
    {
        return $this
            ->baseQuery()
            ->get();
    }
}

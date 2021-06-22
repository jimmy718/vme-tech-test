<?php

namespace App\Queries\Product;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PaginatedProductsQuery extends AbstractProductsQuery
{
    public function run(int $perPage, array $linkAppends): LengthAwarePaginator
    {
        return $this->base()->paginate($perPage)->appends($linkAppends);
    }
}

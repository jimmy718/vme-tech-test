<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Queries\Product\PaginatedProductsQuery;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ProductsController extends Controller
{
    /**
     * @param Request $request
     * @return ResourceCollection
     */
    public function index(Request $request): ResourceCollection
    {
        return ProductResource::collection(
            PaginatedProductsQuery::run($request->input('perPage', 15), $request->query())
        );
    }
}

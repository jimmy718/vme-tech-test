<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Queries\Product\PaginatedProductsQuery;
use App\Queries\Product\ProductsQuery;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ProductsController extends Controller
{
    /**
     * @var PaginatedProductsQuery
     */
    private $paginatedProductsQuery;

    /**
     * @var ProductsQuery
     */
    private $productsQuery;

    /**
     * ProductsController constructor.
     * @param PaginatedProductsQuery $paginatedProductsQuery
     * @param ProductsQuery $productsQuery
     */
    public function __construct(PaginatedProductsQuery $paginatedProductsQuery, ProductsQuery $productsQuery)
    {
        $this->paginatedProductsQuery = $paginatedProductsQuery;
        $this->productsQuery = $productsQuery;
    }

    /**
     * @param Request $request
     * @return ResourceCollection
     */
    public function index(Request $request): ResourceCollection
    {
        return ProductResource::collection(
            $this->paginatedProductsQuery->run($request->input('perPage', 15), $request->query())
        );
    }
}

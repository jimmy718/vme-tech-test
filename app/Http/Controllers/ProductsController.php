<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Queries\Product\PaginatedProductsQuery;
use App\Queries\Product\ProductsQuery;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;

class ProductsController extends Controller
{
    private PaginatedProductsQuery $paginatedProductsQuery;
    private ProductsQuery $productsQuery;

    public function __construct(
        PaginatedProductsQuery $paginatedProductsQuery,
        ProductsQuery $productsQuery
    ) {
        $this->paginatedProductsQuery = $paginatedProductsQuery;
        $this->productsQuery = $productsQuery;
    }

    public function index(Request $request): ResourceCollection
    {
        return ProductResource::collection(
            $this->paginatedProductsQuery->run($request->input('perPage', 15), $request->query())
        );
    }

    public function destroy(Product $product): Response
    {
        $product->delete();

        return response()->noContent();
    }
}

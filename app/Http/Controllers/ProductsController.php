<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Queries\Brand\FirstOrCreateBrandByNameQuery;
use App\Queries\Product\PaginatedProductsQuery;
use App\Queries\Product\ProductsQuery;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;

class ProductsController extends Controller
{
    private FirstOrCreateBrandByNameQuery $firstOrCreateBrandByName;
    private PaginatedProductsQuery $paginatedProductsQuery;
    private ProductsQuery $productsQuery;

    public function __construct(
        FirstOrCreateBrandByNameQuery $firstOrCreateBrandByNameQuery,
        PaginatedProductsQuery $paginatedProductsQuery,
        ProductsQuery $productsQuery
    ) {
        $this->firstOrCreateBrandByName = $firstOrCreateBrandByNameQuery;
        $this->paginatedProductsQuery = $paginatedProductsQuery;
        $this->productsQuery = $productsQuery;
    }

    public function index(Request $request): ResourceCollection
    {
        return ProductResource::collection(
            $this->paginatedProductsQuery->run(
                $request->input('perPage', 15),
                $request->query()
            )
        );
    }

    public function store(StoreProductRequest $request): ProductResource
    {
        return new ProductResource(
            Product::create([
                'name' => $request->input('name'),
                'barcode' => $request->input('barcode'),
                'price' => intval(floatval($request->input('price')) * 100),
                'brand_id' => optional($this->firstOrCreateBrandByName->run($request->input('brand')))->id,
                'image_url' => $request->file('image')->store('product-images', 'images'),
                'date_added' => now(),
            ])->load('brand')
        );
    }

    public function destroy(Product $product): Response
    {
        $product->delete();

        return response()->noContent();
    }
}

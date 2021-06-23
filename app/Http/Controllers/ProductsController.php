<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\Brand;
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
            $this->paginatedProductsQuery->run(
                $request->input('perPage', 15),
                $request->query()
            )
        );
    }

    public function store(Request $request): ProductResource
    {
        $request->validate([
            'barcode' => 'required|numeric',
            'name' => 'required|min:3|max:255',
            'price' => 'required|numeric',
            'brand' => 'nullable|min:3|max:255',
            'image' => 'nullable|image'
        ]);

        return new ProductResource(
            Product::create([
                'barcode' => $request->input('barcode'),
                'name' => $request->input('name'),
                'price' => intval(floatval($request->input('price')) * 100),
                'date_added' => now(),
                'brand_id' => $this->findBrandIdByName($request),
                'image_url' => $request->file('image')->store('product-images', 'images')
            ])->load('brand')
        );
    }

    public function destroy(Product $product): Response
    {
        $product->delete();

        return response()->noContent();
    }

    protected function findBrandIdByName(Request $request): ?int
    {
        if (is_null($request->input('brand'))) {
            return null;
        }

        return Brand::firstOrCreate(['name' => $request->input('brand')])->id;
    }
}

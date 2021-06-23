<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Queries\Brand\FirstOrCreateBrandByNameQuery;
use App\Queries\Product\PaginatedProductsQuery;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;

class ProductsController extends Controller
{
    private FirstOrCreateBrandByNameQuery $firstOrCreateBrandByName;
    private PaginatedProductsQuery $paginatedProductsQuery;

    public function __construct(
        FirstOrCreateBrandByNameQuery $firstOrCreateBrandByNameQuery,
        PaginatedProductsQuery $paginatedProductsQuery,
    ) {
        $this->firstOrCreateBrandByName = $firstOrCreateBrandByNameQuery;
        $this->paginatedProductsQuery = $paginatedProductsQuery;
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
                'price' => $this->preparePrice($request->input('price')),
                'brand_id' => $this->getBrandIdByName($request->input('brand')),
                'image_url' => $this->storeProductImage($request),
                'date_added' => now(),
            ])->load('brand')
        );
    }

    public function update(UpdateProductRequest $request, Product $product): ProductResource
    {
        $product->update([
            'name' => $request->input('name', $product->name),
            'price' => $this->preparePrice($request->input('price', $product->price / 100)),
            'brand_id' => $this->getBrandIdByName($request->input('brand', $product->brand->name)),
            'image_url' => $request->hasFile('image') ? $this->storeProductImage($request) : $product->image_url
        ]);

        return new ProductResource($product);
    }

    public function destroy(Product $product): Response
    {
        $product->delete();

        return response()->noContent();
    }

    protected function getBrandIdByName(?string $brandName): ?int
    {
        return optional($this->firstOrCreateBrandByName->run(
            $brandName
        ))->id;
    }

    protected function preparePrice($price): int
    {
        return intval(floatval($price) * 100);
    }

    protected function storeProductImage(Request $request): string|false
    {
        return $request->file('image')->store('product-images', 'images');
    }
}

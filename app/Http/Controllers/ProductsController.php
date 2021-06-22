<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Queries\Filters\Product\SearchProductNameBarcodeAndBrand;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ProductsController extends Controller
{
    /**
     * @param Request $request
     * @return ResourceCollection
     */
    public function index(Request $request): ResourceCollection
    {
        $products = QueryBuilder::for(Product::class)
            ->allowedSorts('name', 'barcode', 'brand', 'price', 'date_added')
            ->allowedFilters([
                AllowedFilter::custom('search', new SearchProductNameBarcodeAndBrand())
            ])
            ->paginate($request->input('perPage', 15))
            ->appends($request->query());

        return ProductResource::collection($products);
    }
}

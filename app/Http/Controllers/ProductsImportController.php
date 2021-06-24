<?php

namespace App\Http\Controllers;

use App\Jobs\ProductsCsvImportJob;
use App\Queries\Brand\FirstOrCreateBrandByNameQuery;
use Illuminate\Http\Request;

class ProductsImportController extends Controller
{
    public function import(Request $request)
    {
        $path = $request->file('csv')->store('product-imports');

        ProductsCsvImportJob::dispatch($path, new FirstOrCreateBrandByNameQuery());
    }
}

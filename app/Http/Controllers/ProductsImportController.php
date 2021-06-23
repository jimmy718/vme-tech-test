<?php

namespace App\Http\Controllers;

use App\Jobs\ProductsCsvImportJob;
use Illuminate\Http\Request;

class ProductsImportController extends Controller
{
    private ProductsCsvImportJob $productsCsvImportJob;

    public function __construct(ProductsCsvImportJob $productsCsvImportJob)
    {
        $this->productsCsvImportJob = $productsCsvImportJob;
    }

    /**
     * @throws \League\Csv\UnableToProcessCsv
     */
    public function import(Request $request)
    {
        $path = $request->file('csv')->store('product-imports');

        $this->productsCsvImportJob->handle($path);
    }
}

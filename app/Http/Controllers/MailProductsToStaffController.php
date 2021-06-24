<?php

namespace App\Http\Controllers;

use App\Jobs\CreateProductsCsvJob;
use App\Jobs\MailCsvToStaffJob;
use App\Queries\Product\ProductsQuery;
use Illuminate\Http\Request;

class MailProductsToStaffController extends Controller
{
    private ProductsQuery $productsQuery;

    public function __construct(ProductsQuery $productsQuery)
    {
        $this->productsQuery = $productsQuery;
    }

    /**
     * @throws \League\Csv\CannotInsertRecord
     * @throws \League\Csv\Exception
     * @throws \Illuminate\Validation\ValidationException
     */
    public function mail(Request $request)
    {
        $request->validate(['filter' => 'required']);

        $filename = $this->generateCsvFilename();

        CreateProductsCsvJob::dispatch($this->productsQuery->run(), $filename);

        MailCsvToStaffJob::dispatch($filename);
    }

    /**
     * @return string
     */
    protected function generateCsvFilename(): string
    {
        return 'labels/' . now()->toIso8601String() . '.csv';
    }
}

<?php

namespace App\Http\Controllers;

use App\Mail\LabelsCsvMail;
use App\Models\Product;
use App\Queries\Product\ProductsQuery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use League\Csv\Writer;

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

        $products = $this->productsQuery->run();

        $writer = Writer::createFromString();

        $writer->insertOne([
            'name',
            'barcode',
            'brand',
            'price',
        ]);

        $products = $products->map(function (Product $product) {
            return [
                $product->name,
                $product->barcode,
                $product->brand->name,
                number_format($product->price * 100, 2),
            ];
        });

        $writer->insertAll($products);

        $filename = 'labels/' . now()->toIso8601String() . '.csv';
        Storage::put($filename, $writer->toString());

        Mail::to('staff@co-op-shopper.co.uk')->send(new LabelsCsvMail($filename));
    }
}

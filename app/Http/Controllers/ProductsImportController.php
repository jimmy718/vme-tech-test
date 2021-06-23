<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use League\Csv\Reader;

class ProductsImportController extends Controller
{
    /**
     * @throws \League\Csv\UnableToProcessCsv
     */
    public function import(Request $request)
    {
        Storage::fake('s3');

        $path = $request->file('csv')->store('product-imports');

        $reader = Reader::createFromString(Storage::get($path));

        $headers = $reader->fetchOne();

        for ($index = 1; $index < $reader->count(); $index++) {
            $row = $reader->fetchOne($index);
            Product::create([
                'name' => trim($row[$this->getIndexOfHeader('name', $headers)]),
                'barcode' => trim($row[$this->getIndexOfHeader('barcode', $headers)]),
                'brand_id' => $this->getBrandIdByName($row[$this->getIndexOfHeader('brand', $headers)]),
                'price' => intval(floatval($row[$this->getIndexOfHeader('price', $headers)]) * 100),
                'image_url' => trim($row[$this->getIndexOfHeader('image_url', $headers)]),
                'date_added' => Carbon::createFromFormat(
                    'd/m/Y H:i:s',
                    trim($row[$this->getIndexOfHeader('date_added', $headers)])
                )
            ]);
        }
    }

    protected function getIndexOfHeader(string $header, array $headers): int
    {
        return array_search($header, $headers);
    }

    protected function getBrandIdByName($input)
    {
        if (!trim($input)) {
            return null;
        }

        return Brand::firstOrCreate(['name' => trim($input)])->id;
    }
}

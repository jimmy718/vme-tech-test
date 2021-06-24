<?php

namespace App\Jobs;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Collection as BaseCollection;
use Illuminate\Support\Facades\Storage;
use League\Csv\Writer;

class CreateProductsCsvJob
{
    use Dispatchable;

    private Collection $products;
    private string $filename;

    public function __construct(Collection $products, string $filename)
    {
        $this->products = $products;
        $this->filename = $filename;
    }

    /**
     * @throws \League\Csv\CannotInsertRecord
     * @throws \League\Csv\Exception
     */
    public function handle()
    {
        $writer = $this->prepareCsvWriter();

        $writer->insertAll(
            $this->mapProductsForCsv($this->products)
        );

        $this->storeCsvFile($writer);
    }

    /**
     * @throws \League\Csv\CannotInsertRecord
     */
    protected function prepareCsvWriter(): Writer
    {
        $writer = Writer::createFromString();

        $writer->insertOne([
            'name',
            'barcode',
            'brand',
            'price',
        ]);

        return $writer;
    }

    protected function mapProductsForCsv(Collection $products): BaseCollection
    {
        return $products->map(function (Product $product) {
            return [
                $product->name,
                $product->barcode,
                $product->brand->name,
                number_format($product->price * 100, 2),
            ];
        });
    }

    /**
     * @throws \League\Csv\Exception
     */
    protected function storeCsvFile(Writer $writer): void
    {
        Storage::put($this->filename, $writer->toString());
    }
}

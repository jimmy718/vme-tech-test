<?php

namespace App\Jobs;

use App\Models\Product;
use App\Queries\Brand\FirstOrCreateBrandByNameQuery;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use League\Csv\Reader;

class ProductsCsvImportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private array $csvHeaders = [];
    private string $csvFilePath;
    private FirstOrCreateBrandByNameQuery $firstOrCreateBrandByName;

    public function __construct(
        string $csvFilePath,
        FirstOrCreateBrandByNameQuery $firstOrCreateBrandByNameQuery,
    ) {
        $this->csvFilePath = $csvFilePath;
        $this->firstOrCreateBrandByName = $firstOrCreateBrandByNameQuery;
    }

    /**
     * @throws \League\Csv\UnableToProcessCsv
     */
    public function handle(): void
    {
        $reader = Reader::createFromPath($this->csvFilePath);

        $this->csvHeaders = $reader->fetchOne();

        for ($index = 1; $index < $reader->count(); $index++) {
            $this->createProductFromRow($reader->fetchOne($index));
        }
    }

    protected function getItemFromRow(array $row, string $key): string
    {
        return trim($row[array_search($key, $this->csvHeaders)]);
    }

    protected function createProductFromRow(array $row): void
    {
        Product::create([
            'name' => $this->getItemFromRow($row, 'name'),
            'barcode' => $this->getItemFromRow($row, 'barcode'),
            'brand_id' => $this->getBrandId($row),
            'price' => intval(floatval($this->getItemFromRow($row, 'price')) * 100),
            'image_url' => $this->getItemFromRow($row, 'image_url'),
            'date_added' => Carbon::createFromFormat(
                'd/m/Y H:i:s',
                $this->getItemFromRow($row, 'date_added')
            )
        ]);
    }

    protected function getBrandId(array $row): ?int
    {
        $brand = $this->firstOrCreateBrandByName->run(
            $this->getItemFromRow($row, 'brand')
        );

        return optional($brand)->id;
    }
}

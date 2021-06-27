<?php

namespace App\Console\Commands;

use App\Jobs\ProductsCsvImportJob;
use Illuminate\Console\Command;

class ImportLegacyProductsCsv extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:legacy';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import legacy products from csv';

    /**
     * @var ProductsCsvImportJob
     */
    private ProductsCsvImportJob $csvImport;

    /**
     * ImportLegacyProductsCSV constructor.
     * @param ProductsCsvImportJob $csvImport
     */
    public function __construct(ProductsCsvImportJob $csvImport)
    {
        parent::__construct();

        $this->csvImport = $csvImport;
    }

    /**
     * Execute the console command.
     *
     * @return int
     * @throws \League\Csv\UnableToProcessCsv
     */
    public function handle()
    {
        $this->csvImport->handle();

        return 0;
    }
}

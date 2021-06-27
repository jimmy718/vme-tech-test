<?php

namespace App\Console\Commands;

use App\Jobs\ProductsCsvImportJob;
use App\Queries\Brand\FirstOrCreateBrandByNameQuery;
use Illuminate\Console\Command;

class ImportLegacyProductsCsv extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:legacy {filepath?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import legacy products from csv';

    /**
     * Execute the console command.
     *
     * @return int
     * @throws \League\Csv\UnableToProcessCsv
     */
    public function handle()
    {
        ProductsCsvImportJob::dispatch(
            $this->argument('filepath') ?? 'app/Console/Commands/legacy_products.csv',
            new FirstOrCreateBrandByNameQuery(),
        );

        return 0;
    }
}

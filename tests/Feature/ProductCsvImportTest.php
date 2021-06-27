<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProductCsvImportTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_products_from_csv()
    {
        Artisan::call('import:legacy tests/Feature/test_legacy_products.csv');

        $this->assertDatabaseHas('products', [
            'name' => 'Rainbow Cookies 225g',
            'barcode' => '25040227',
            'brand_id' => Brand::where('name', 'Rainbow')->value('id'),
            'price' => 394,
            'date_added' => '2021-02-23 17:50:02'
        ]);

        $this->assertDatabaseHas('products', [
            'name' => 'Babybel Mini Natural Cheese 20g',
            'barcode' => '30116269',
            'brand_id' => Brand::where('name', 'Babybel')->value('id'),
            'price' => 662,
            'date_added' => '2021-02-22 17:50:28'
        ]);
    }
}

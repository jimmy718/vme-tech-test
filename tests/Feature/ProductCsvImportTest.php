<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProductCsvImportTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_products_from_csv()
    {
        $header = 'name,barcode,brand,price,image_url,date_added';
        $row1 = 'Rainbow Cookies 225g,25040227,Rainbow,3.94,https://picsum.photos/500,23/02/2021 17:50:02';
        $row2 = 'Babybel Mini Natural Cheese 20g,30116269,Babybel,6.62,https://picsum.photos/500,22/02/2021 17:50:28';

        $content = implode("\n", [$header, $row1, $row2]);

        $this
            ->actingAs(User::factory()->create())
            ->postJson(route('imports.products'), [
                'csv' => UploadedFile::fake()->createWithContent('legacy_products.csv', $content)
            ])
            ->assertOk();

        $this->assertDatabaseHas('products', [
            'name' => 'Rainbow Cookies 225g',
            'barcode' => '25040227',
            'brand_id' => Brand::where('name', 'Rainbow')->value('id'),
            'price' => 394,
            'image_url' => 'https://picsum.photos/500',
            'date_added' => '2021-02-23 17:50:02'
        ]);

        $this->assertDatabaseHas('products', [
            'name' => 'Babybel Mini Natural Cheese 20g',
            'barcode' => '30116269',
            'brand_id' => Brand::where('name', 'Babybel')->value('id'),
            'price' => 662,
            'image_url' => 'https://picsum.photos/500',
            'date_added' => '2021-02-22 17:50:28'
        ]);
    }
}

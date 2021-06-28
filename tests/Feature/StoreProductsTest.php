<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class StoreProductsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('images');
    }

    protected function validPayload(array $overrides = [])
    {
        return array_merge([
            'barcode' => '0123456789',
            'name' => 'Half Coated Chocolate Ginger Biscuit',
            'brand' => 'Watson\'s Bakery',
            'price' => 9.99,
            'image' => UploadedFile::fake()->image('chocolate-ginger-biscuit.jpg')
        ], $overrides);
    }

    /** @test */
    public function guests_cannot_store_products()
    {
        $this->postJson(route('products.store'), [])->assertUnauthorized();
    }

    /** @test */
    public function users_can_store_products()
    {
        $payload = $this->validPayload();

        $this
            ->actingAs(User::factory()->create())
            ->postJson(route('products.store'), $payload)
            ->assertCreated()
            ->assertJsonFragment([
                'price' => '9.99'
            ])
            ->assertJsonFragment([
                'name' => $payload['brand']
            ]);

        Storage::disk('images')
            ->assertExists(
                'product-images/' . $payload['image']->hashName()
            );
    }

    /** @test */
    public function barcode_is_required()
    {
        $this
            ->actingAs(User::factory()->create())
            ->postJson(route('products.store'), $this->validPayload([
                'barcode' => null
            ]))->assertJsonValidationErrors([
                'barcode'
            ]);
    }

    /** @test */
    public function barcode_must_be_numeric()
    {
        $this
            ->actingAs(User::factory()->create())
            ->postJson(route('products.store'), $this->validPayload([
                'barcode' => 'abc123'
            ]))->assertJsonValidationErrors([
                'barcode'
            ]);

    }

    /** @test */
    public function name_is_required()
    {
        $this
            ->actingAs(User::factory()->create())
            ->postJson(route('products.store'), $this->validPayload([
                'name' => null
            ]))->assertJsonValidationErrors([
                'name'
            ]);
    }

    /** @test */
    public function name_must_be_at_least_3_chars()
    {
        $this
            ->actingAs(User::factory()->create())
            ->postJson(route('products.store'), $this->validPayload([
                'name' => 'AB'
            ]))->assertJsonValidationErrors([
                'name'
            ]);
    }

    /** @test */
    public function price_is_required()
    {
        $this
            ->actingAs(User::factory()->create())
            ->postJson(route('products.store'), $this->validPayload([
                'price' => null
            ]))->assertJsonValidationErrors([
                'price'
            ]);
    }

    /** @test */
    public function price_must_be_numeric()
    {
        $this
            ->actingAs(User::factory()->create())
            ->postJson(route('products.store'), $this->validPayload([
                'price' => 'abcdef'
            ]))->assertJsonValidationErrors([
                'price'
            ]);
    }

    /** @test */
    public function brand_must_be_at_least_3_chars()
    {
        $this
            ->actingAs(User::factory()->create())
            ->postJson(route('products.store'), $this->validPayload([
                'brand' => 'AB'
            ]))->assertJsonValidationErrors([
                'brand'
            ]);
    }

    /** @test */
    public function brand_is_optional_and_should_remain_null_when_not_given()
    {
        $this
            ->actingAs(User::factory()->create())
            ->postJson(route('products.store'), $this->validPayload([
                'brand' => null
            ]))
            ->assertJsonFragment([
                'brand' => null
            ]);
    }

    /** @test */
    public function image_must_be_an_image_file()
    {
        $this
            ->actingAs(User::factory()->create())
            ->postJson(route('products.store'), $this->validPayload([
                'image' => UploadedFile::fake()->create('document.pdf')
            ]))->assertJsonValidationErrors([
                'image'
            ]);
    }

    /** @test */
    public function image_must_be_no_larger_than_512KB()
    {
        $this
            ->actingAs(User::factory()->create())
            ->postJson(route('products.store'), $this->validPayload([
                'image' => UploadedFile::fake()->create('document.pdf', 513)
            ]))->assertJsonValidationErrors([
                'image'
            ]);
    }
}

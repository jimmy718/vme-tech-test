<?php

namespace Tests\Feature;

use App\Mail\ProductUpdateMail;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UpdateProductsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guests_cannot_update_products()
    {
        $product = Product::factory()->create();

        $this->putJson(route('products.update', $product))->assertUnauthorized();
    }

    /** @test */
    public function name_can_be_edited()
    {
        /** @var Product $product */
        $product = Product::factory()->create();

        $this
            ->actingAs(User::factory()->create())
            ->putJson(route('products.update', $product), [
                'name' => 'updated name'
            ])
            ->assertOk();

        $this->assertEquals('updated name', $product->fresh()->name);
    }

    /** @test */
    public function brand_can_be_edited()
    {
        /** @var Product $product */
        $product = Product::factory()->create();

        $this
            ->actingAs(User::factory()->create())
            ->putJson(route('products.update', $product), [
                'brand' => 'updated brand'
            ])
            ->assertOk();

        $this->assertEquals('updated brand', $product->fresh()->brand->name);
    }

    /** @test */
    public function image_can_be_edited()
    {
        Storage::fake('images');

        /** @var Product $product */
        $product = Product::factory()->create();

        $testImage = UploadedFile::fake()->image('caramel-latte-ice-cream.png');

        $this
            ->actingAs(User::factory()->create())
            ->putJson(route('products.update', $product), [
                'image' => $testImage
            ])
            ->assertOk();

        Storage::disk('images')
            ->assertExists(
                'product-images/' . $testImage->hashName()
            );

        $this->assertNotEquals($product->image_url, $product->fresh()->image_url);
    }

    /** @test */
    public function when_image_is_edited_old_image_is_deleted()
    {
        Storage::fake('images');

        $originalImagePath = UploadedFile::fake()->image('image-a.png')->store('product-images', 'images');

        /** @var Product $product */
        $product = Product::factory()->create(['image_url' => $originalImagePath]);

        $newImage = UploadedFile::fake()->image('image-b.png');

        $this
            ->actingAs(User::factory()->create())
            ->putJson(route('products.update', $product), [
                'image' => $newImage
            ])
            ->assertOk();

        Storage::disk('images')->assertExists('product-images/' . $newImage->hashName());
        Storage::disk('images')->assertMissing($originalImagePath);
    }

    /** @test */
    public function price_can_be_edited()
    {
        /** @var Product $product */
        $product = Product::factory()->create();

        $this
            ->actingAs(User::factory()->create())
            ->putJson(route('products.update', $product), [
                'price' => 15.99
            ])
            ->assertOk();

        $this->assertEquals(1599, $product->fresh()->price);
    }

    /** @test */
    public function when_a_product_is_updated_a_staff_email_is_sent_to_notify()
    {
        $this->withoutExceptionHandling();
        Mail::fake();

        /** @var Product $product */
        $product = Product::factory()->create();

        $oldPrice = number_format($product->price / 100, 2);

        $this
            ->actingAs(User::factory()->create())
            ->putJson(route('products.update', $product), [
                'price' => 15.99
            ])
            ->assertOk();

        Mail::assertSent(ProductUpdateMail::class, function (ProductUpdateMail $mail) use ($oldPrice) {
            return $oldPrice === $mail->oldValues['price'] && '15.99' === $mail->newValues['price'];
        });
    }
}

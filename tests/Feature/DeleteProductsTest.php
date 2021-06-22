<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeleteProductsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guests_cannot_delete_products()
    {
        $product = Product::factory()->create();

        $this->deleteJson(route('products.destroy', $product))->assertUnauthorized();
    }

    /** @test */
    public function users_can_delete_products()
    {
        $product = Product::factory()->create();

        $this
            ->actingAs(User::factory()->create())
            ->deleteJson(route('products.destroy', $product))
            ->assertNoContent();
    }
}

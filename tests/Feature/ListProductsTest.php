<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ListProductsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guests_cannot_list_products()
    {
        $this->getJson(route('products.index'))->assertUnauthorized();
    }

    /** @test */
    public function users_can_list_paginated_products()
    {
        Product::factory()->count(4)->create();

        $response = $this
            ->actingAs(User::factory()->create())
            ->getJson(route('products.index', ['perPage' => 2]))
            ->assertOk()
            ->assertJsonCount(2, 'data')
            ->assertJsonStructure(
                $this->paginationStructure()
            );

        $nextPageUrl = json_decode($response->content())->links->next;

        $this
            ->getJson($nextPageUrl)
            ->assertOk()
            ->assertJsonCount(2, 'data')
            ->assertJsonStructure(
                $this->paginationStructure()
            );
    }

    /**
     * @return array
     */
    protected function paginationStructure(): array
    {
        return [
            'data' => [
                0 => [
                    'id', 'name', 'barcode', 'brand', 'price', 'image_url', 'date_added'
                ],
                1 => [
                    'id', 'name', 'barcode', 'brand', 'price', 'image_url', 'date_added'
                ],
            ],
            'links' => [
                'first', 'last', 'prev', 'next'
            ],
            'meta' => [
                'current_page', 'from', 'last_page', 'path', 'per_page', 'to', 'total'
            ]
        ];
    }
}

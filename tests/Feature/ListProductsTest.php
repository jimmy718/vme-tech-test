<?php

namespace Tests\Feature;

use App\Models\Brand;
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

        $nextPageUrl = $response->json()['links']['next'];

        $this
            ->getJson($nextPageUrl)
            ->assertOk()
            ->assertJsonCount(2, 'data')
            ->assertJsonStructure(
                $this->paginationStructure()
            );
    }

    /** @test */
    public function paginated_products_can_be_searched_by_name_barcode_and_brand()
    {
        $included = [
            Product::factory()->create(['name' => 'test name']),
            Product::factory()->create(['barcode' => 'test barcode']),
            Product::factory()->create([
                'brand_id' => Brand::factory()->create(['name' => 'test brand'])->id
            ]),
        ];

        $excluded = [
            Product::factory()->create(['name' => 'not included']),
            Product::factory()->create(['barcode' => 'not included']),
            Product::factory()->create([
                'brand_id' => Brand::factory()->create(['name' => 'not included'])->id
            ]),
        ];

        $response = $this
            ->actingAs(User::factory()->create())
            ->getJson(route('products.index', [
                'filter' => [
                    'search' => 'test'
                ]
            ]))
            ->assertOk()
            ->assertJsonCount(count($included), 'data');

        foreach ($included as $product) {
            $response->assertJsonFragment(['id' => $product->id]);
        }

        foreach ($excluded as $product) {
            $response->assertJsonMissing(['id' => $product->id]);
        }
    }

    /** @test */
    public function products_can_be_filtered_by_brand()
    {
        $this->withoutExceptionHandling();
        $included = Product::factory()->count(2)->create([
            'brand_id' => Brand::factory()->create(['name' => 'test brand'])
        ]);

        $excluded = Product::factory()->create();

        $response = $this
            ->actingAs(User::factory()->create())
            ->getJson(route('products.index', [
                'filter' => [
                    'brand' => 'test'
                ]
            ]))
            ->assertOk()
            ->assertJsonCount(count($included), 'data');

        $response->assertJsonMissing(['id' => $excluded->id]);

        foreach ($included as $product) {
            $response->assertJsonFragment(['id' => $product->id]);
        }
    }

    /** @test */
    public function products_can_be_filtered_by_price_range()
    {
        $included = [
            Product::factory()->create(['price' => 550]),
            Product::factory()->create(['price' => 750]),
            Product::factory()->create(['price' => 1099]),
        ];

        $excluded = [
            Product::factory()->create(['price' => 549]),
            Product::factory()->create(['price' => 1100]),
        ];

        $response = $this
            ->actingAs(User::factory()->create())
            ->getJson(route('products.index', [
                'filter' => [
                    'price-range' => '5.50-10.99'
                ]
            ]))
            ->assertOk()
            ->assertJsonCount(count($included), 'data');

        foreach ($included as $product) {
            $response->assertJsonFragment(['id' => $product->id]);
        }

        foreach ($excluded as $product) {
            $response->assertJsonMissing(['id' => $product->id]);
        }
    }

    /** @test */
    public function products_can_be_sorted_by_name()
    {
        Product::factory()->create(['name' => 'test b']);
        Product::factory()->create(['name' => 'test a']);
        Product::factory()->create(['name' => 'test c']);

        $response = $this
            ->actingAs(User::factory()->create())
            ->getJson(route('products.index', [
                'sort' => [
                    '-name'
                ]
            ]))
            ->assertOk()
            ->assertJsonCount(3, 'data');

        $products = $response->json()['data'];

        $this->assertEquals('test c', $products[0]['name']);
        $this->assertEquals('test b', $products[1]['name']);
        $this->assertEquals('test a', $products[2]['name']);
    }

    /**
     * @return array
     */
    protected function paginationStructure(): array
    {
        return [
            'data' => [
                0 => $this->getProductStructure(),
                1 => $this->getProductStructure(),
            ],
            'links' => [
                'first', 'last', 'prev', 'next'
            ],
            'meta' => [
                'current_page', 'from', 'last_page', 'path', 'per_page', 'to', 'total'
            ]
        ];
    }

    /**
     * @return string[]
     */
    protected function getProductStructure(): array
    {
        return [
            'id',
            'name',
            'barcode',
            'price',
            'image_url',
            'date_added',
            'brand' => [
                'id', 'name'
            ]
        ];
    }
}

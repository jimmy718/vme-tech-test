<?php

namespace Database\Factories;

use App\Models\Brand;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->words(rand(1, 4), true),
            'barcode' => $this->faker->ean13,
            'brand_id' => Brand::factory(),
            'price' => rand(100, 1000),
            'image_url' => 'https://picsum.photos/500',
            'date_added' => Carbon::parse($this->faker->dateTime)
        ];
    }
}

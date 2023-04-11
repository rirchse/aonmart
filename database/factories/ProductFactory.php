<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Store;
use App\Models\Unit;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;


    public function configure(): ProductFactory
    {
        return $this->afterCreating(function (Product $product) {
            $store = Store::first();
            if (empty($store)) {
                $store = Store::factory()->create();
            }
            $product->stores()->attach([
                $store->id => [
                    'stock' => 0,
                    'stock_out' => 0
                ]
            ]);
        });
    }

    public function definition(): array
    {
        return [
            'barcode' => $this->faker->randomNumber(8),
            'name' => $this->faker->word(),
            'short_description' => $this->faker->text(20),
            'full_description' => $this->faker->text(30),
            'regular_price' => $this->faker->randomNumber(3),
            'featured' => $this->faker->boolean,
            'unit_id' => Unit::factory()->create()->id,
            'quantity' => rand(1, 10),
        ];
    }
}

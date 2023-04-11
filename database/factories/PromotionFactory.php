<?php

namespace Database\Factories;

use App\Models\Promotion;
use App\Models\Store;
use Illuminate\Database\Eloquent\Factories\Factory;

class PromotionFactory extends Factory
{
    protected $model = Promotion::class;


    public function configure(): PromotionFactory
    {
        return $this->afterCreating(function (Promotion $promotion) {
            $promotion->details()->attach(
                $promotion->store->products()->pluck('products.id'),
                [
                    'price' => $this->faker->randomNumber(3)
                ]
            );
        });
    }

    public function definition(): array
    {
        return [
            'store_id' => Store::factory()->create()->id,
            'title' => $this->faker->sentence(),
            'start_date' => $this->faker->date(),
            'end_date' => $this->faker->date(),
        ];
    }
}

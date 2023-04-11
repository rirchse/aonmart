<?php

namespace Database\Factories;

use App\Models\Banner;
use App\Models\Category;
use App\Models\Store;
use Illuminate\Database\Eloquent\Factories\Factory;

class StoreFactory extends Factory
{
    protected $model = Store::class;

    public function configure(): StoreFactory
    {
        return $this->afterCreating(function (Store $store) {
            $store->categories()->attach(
                Category::factory($this->faker->numberBetween(1, 3))
                    ->create()
                    ->pluck('id')
            );

            Banner::insertDefaultBannersForStore($store);
        });
    }

    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'address' => $this->faker->address(),
            'lat' => $this->faker->latitude(),
            'lng' => $this->faker->longitude()
        ];
    }
}

<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\SubSubcategory;
use App\Models\Subcategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class SubSubcategoryFactory extends Factory
{
    protected $model = SubSubcategory::class;

    public function definition(): array
    {
        $category = Category::factory()->has(
            Subcategory::factory()
        )->create();
        $subcategory = Subcategory::with(['Category'])->get()->random();
        return [
            'name' => $this->faker->word(),
            'category_id' => $category->id,
            'subcategory_id' => $category->subcategories->random()->id
        ];
    }
}

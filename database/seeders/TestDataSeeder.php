<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Promotion;
use App\Models\Purchase;
use App\Models\Unit;
use App\Models\User;
use App\Models\Store;
use App\Models\Product;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\SubSubcategory;
use Illuminate\Database\Seeder;

class TestDataSeeder extends Seeder
{
    public function run(): void
    {
        Company::factory(10)->create();
        Category::factory(10)->create();
        Subcategory::factory(10)->create();
        SubSubcategory::factory(10)->create();
        $store = Store::factory()->create([
            'name' => 'Common Store'
        ]);
        Unit::factory(10)->create();
        User::factory(10)->create();
        Product::factory(10)->create();
        Promotion::factory(10)->create([
            'store_id' => $store->id
        ]);
        Purchase::factory(10)->create([
            'store_id' => $store->id
        ]);
    }
}

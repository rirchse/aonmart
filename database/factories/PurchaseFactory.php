<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\Store;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Spatie\Permission\Models\Role;

class PurchaseFactory extends Factory
{
    protected $model = Purchase::class;

    public function configure(): PurchaseFactory
    {
        return $this->afterCreating(function (Purchase $purchase) {
            $attachableData = [];
            $products = Product::factory(rand(1, 5))->create();

            $totalAmount = 0;
            $totalQuantity = 0;
            foreach ($products as $product) {
                $quantity = rand(1, $product->quantity);
                $attachableData[$product->id] = [
                    'qty' => $quantity,
                    'discount' => 0,
                    'product_price' => $product->regular_price,
                    'total_price' => $product->regular_price * $quantity,
                    'unit' => $product->regular_price
                ];
                $totalAmount += $product->regular_price * $quantity;
                $totalQuantity += $quantity;
            }

            $purchase->products()
                ->attach($attachableData);
            $purchase->total_qty = $totalQuantity;
            $purchase->grand_total = $totalAmount;
            $purchase->save();
        });
    }

    public function definition(): array
    {
        $supplerRole = Role::findByName(User::SUPPLIER_ROLE);
        return [
            'invoice_no' => $this->faker->unique()->randomNumber(6),
            'purchase_date' => Carbon::now(),
            'supplier_id' => User::factory()->hasAttached($supplerRole)->create()->id,
            'purchase_note' => "Purchase note",
            'grand_total' => 0,
            'total_qty' => 0,
            'due_amount' => 0,
            'status' => rand(0, 1),
            'store_id' => Store::factory()->create()->id,
        ];
    }
}

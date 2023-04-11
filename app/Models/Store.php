<?php

namespace App\Models;

use DB;
use Exception;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Log;
use Throwable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Store extends Model
{
    use HasFactory;

    protected $casts = [
        'shipping_fee' => 'integer'
    ];

    protected $fillable = [
        'name', 'lat', 'lng', 'created_at', 'updated_at', 'image',
        'status', 'address', 'shipping_fee', 'capital', 'capital_note'
    ];

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'store_categories');
    }


    //    Old methods

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class)->withPivot('stock', 'stock_out')->withTimestamps();
    }

    public function employees(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }

    /**
     * Reset a store system
     *
     * @throws Exception
     */
    public function resetStore(): bool
    {
        if (!$this->exists) throw new Exception('Store not found');

        try {
            DB::beginTransaction();

            Sale::where('store_id', $this->id)
                ->delete();

            Order::where('store_id', $this->id)
                ->delete();

            $warehouseToStores = WarehouseToStore::where('store_id', $this->id)
                ->with('products')
                ->get();

            // Update all products stock
            $productStock = [];
            foreach ($warehouseToStores as $warehouseToStore) {
                foreach ($warehouseToStore->products as $product) {
                    $productStock[$product->id] = [
                        'id' => $product->id,
                        'stock' => $product->stock,
                        'stock_out' => $product->stock_out - $product->pivot->quantity,
                        'name' => $product->name,
                        'regular_price' => $product->regular_price,
                    ];
                }
            }

            $purchases = Purchase::where('store_id', $this->id)
                ->with('products')
                ->get();

            // update all products stock
            foreach ($purchases as $purchase) {
                foreach ($purchase->products as $product) {
                    if (array_key_exists($product->id, $productStock)) {
                        $productStock[$product->id]['stock'] -= $product->pivot->qty;
                    } else {
                        $productStock[$product->id] = [
                            'id' => $product->id,
                            'stock' => $product->stock - $product->pivot->qty,
                            'stock_out' => $product->stock_out,
                            'name' => $product->name,
                            'regular_price' => $product->regular_price,
                        ];
                    }
                }
            }

            Product::upsert($productStock, [
                'id'
            ], [
                'stock', 'stock_out'
            ]);

            WarehouseToStore::where('store_id', $this->id)
                ->delete();

            Purchase::where('store_id', $this->id)
                ->delete();

            Expense::where('store_id', $this->id)
                ->delete();

            Video::where('store_id', $this->id)
                ->delete();

            User::where('store_id', $this->id)->update([
                'store_id' => null
            ]);

            DB::commit();
        } catch (Throwable $throwable) {
            DB::rollBack();
            $status = false;

            Log::error('STORE RESET ERROR', [
                'store_id' => $this->id,
                'error' => $throwable->getMessage()
            ]);
        }
        return $status ?? true;
    }
}

<?php

namespace App\Models;

use App\Library\Utilities;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'barcode',
        'short_description',
        'full_description',
        'regular_price',
        'quantity',
        'unit_id',
        'sell_price',
        'discount',
        'stock',
        'image',
        'gallery',
        'status',
        'featured'
    ];

    // Relations
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    public function subcategories(): BelongsToMany
    {
        return $this->belongsToMany(Subcategory::class);
    }

    public function subSubcategories(): BelongsToMany
    {
        return $this->belongsToMany(SubSubcategory::class);
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function tags(): HasMany
    {
        return $this->hasMany(ProductTag::class);
    }

    public function featureProducts(): BelongsToMany
    {
        return $this->belongsToMany(FeatureProducts::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function sales(): BelongsToMany
    {
        return $this->belongsToMany(Sale::class)->withPivot('quantity', 'product_price', 'total_price');
    }

    public function purchases(): BelongsToMany
    {
        return $this->belongsToMany(Purchase::class)->withPivot('qty', 'discount', 'product_price', 'total_price', 'product_price', 'unit');
    }
    public function product_purchase($id)
    {
        return DB::table('product_purchase')->where('product_id', $id)->first();
    }

    public function stores(): BelongsToMany
    {
        return $this->belongsToMany(Store::class)->withPivot('stock', 'stock_out')->withTimestamps();
    }

    public function warehouseToStores(): BelongsToMany
    {
        return $this->belongsToMany(WarehouseToStore::class)->withPivot('quantity')->withTimestamps();
    }

    public function scopeFeatured($query)
    {
        return $query->whereFeatured(true);
    }

    public function promotion(): BelongsToMany
    {
        return $this->belongsToMany(Promotion::class, 'promotion_products')->withPivot('price');
    }

    public function returns(): BelongsToMany
    {
        return $this->belongsToMany(ReturnProduct::class)->withPivot('note', 'status', 'qty', 'total');
    }

    public function suppliers(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }


    public function wishlistUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'wishlist');
    }

    // Attributes
    protected function imageUrl(): Attribute
    {
        return Attribute::make(
            get: fn() => Utilities::getImageUrl($this->image)
        );
    }

    protected function inStock(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->quantity - $this->stock_out
        );
    }

    protected function formattedInStock(): Attribute
    {
        return Attribute::make(
            get: fn() => "{$this->in_stock} {$this->unit->name}"
        );
    }

    protected function storeInStock(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->pivot->stock - $this->pivot->stock_out
        );
    }

    protected function formattedStoreInStock(): Attribute
    {
        return Attribute::make(
            get: fn() => "{$this->store_in_stock} {$this->unit->name}"
        );
    }

    protected function price(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->sell_price ?: $this->regular_price
        );
    }

    protected function formattedPrice(): Attribute
    {
        return Attribute::make(
            get: fn() => Utilities::formattedAmount($this->price)
        );
    }

    // Others
    public function storeStock($store_id): int
    {
        $storeStock = $this->stores->where('id', $store_id)->first();
        return ($storeStock->pivot->stock ?? 0) - ($storeStock->pivot->stock_out ?? 0);
    }

    public function orderProducts()
    {
        return $this->hasMany(OrderProduct::class);
    }

    public function productQuery($store)
    {
        return $this->query()
        ->join('product_purchase', 'products.id', 'product_purchase.product_id')
        ->join('purchases', 'purchases.id', 'product_purchase.purchase_id')
        ->when($store, function ($query) use ($store) {
            return $query->where('store_id', $store->id);
        });
    }
}

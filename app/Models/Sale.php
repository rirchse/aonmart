<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sale extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class)->withPivot('quantity', 'product_price', 'total_price');
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function productReturn(): HasMany
    {
        return $this->hasMany(ReturnProduct::class);
    }

    public function scopeSearch($query, $input = null)
    {
        return $query->where('number_sale', 'like', '%'.$input.'%');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id',
        'code',
        'discount',
        'expire_at',
        'status',
    ];

    public function couponProducts(): HasMany
    {
        return $this->hasMany(CouponProduct::class);
    }
}

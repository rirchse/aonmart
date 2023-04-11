<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CouponProduct extends Model
{
    use HasFactory;

    protected $fillable = ['coupon_id', 'product_id'];

    public function coupon(): BelongsTo
    {
      return $this->belongsTo(Coupon::class);
    }

    public function product(): BelongsTo
    {
      return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}

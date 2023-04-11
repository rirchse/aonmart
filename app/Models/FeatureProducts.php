<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FeatureProducts extends Model
{
    use HasFactory;

    protected $fillable = [
      'product_id','feature_id'
    ];

    public function features(): BelongsTo
    {
      return $this->belongsTo(Feature::class);
    }

    public function products(): BelongsTo
    {
      return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}

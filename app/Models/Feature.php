<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Feature extends Model
{
    use HasFactory;

    protected $fillable = [
      'name','image', 'priority','status'
    ];

    public function featureProducts(): HasMany
    {
      return $this->hasMany(FeatureProducts::class);
    }
}

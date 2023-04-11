<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FeatureCategory extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function category(): BelongsTo
    {
      return $this->belongsTo(Category::class);
    }
}


<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Promotion extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $dates = ['start_date', 'end_date'];

    public function details(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'promotion_products')->withPivot('price');
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }
}

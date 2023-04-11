<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subject extends Model
{
    use HasFactory;

    Protected $fillable = [
        'icon', 'name', 'details', 'status'
    ];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\SubSubcategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'icon', 'name', 'cover_img', 'description', 'status'
    ];

    public function stores(): BelongsToMany
    {
        return $this->belongsToMany(Store::class, 'store_categories');
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class);
    }

    public function subcategories(): HasMany
    {
        return $this->hasMany(Subcategory::class);
    }

    public function subSubcategories(): HasMany
    {
        return $this->hasMany(SubSubcategory::class);
    }

}

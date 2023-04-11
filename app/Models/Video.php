<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Video extends Model
{
    use HasFactory;

    protected $fillable = [
        'thumbnail', 'title', 'youtube_uid', 'link', 'status', 'store_id'
    ];

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }
}

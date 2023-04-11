<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Feedback extends Model
{
    use HasFactory;

    const REFERENCE_ORDER = "ORDER";

    const REFERENCES = [
        self::REFERENCE_ORDER => Order::class
    ];

    protected $fillable = [
        'user_id', 'store_id', 'content', 'status', 'referencable_id', 'referencable_type'
    ];

    public function feedbackable(): MorphTo
    {
        return $this->morphTo();
    }
}

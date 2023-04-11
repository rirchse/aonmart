<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    use HasFactory;

    const TYPE_DEFAULT = 1;
    const TYPE_HOW_TO_ORDER = 2;

    const TYPES = [
      self::TYPE_DEFAULT => 'Default Slide',
      self::TYPE_HOW_TO_ORDER => 'How To Order Slide'
    ];

    protected $fillable = [
        'store_id',
        'image',
        'title',
        'subtitle',
        'button_text',
        'button_link',
        'type',
        'status'
    ];
}

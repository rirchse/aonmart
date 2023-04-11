<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EcommerceSetting extends Model
{
    use HasFactory;
    
    protected $fillable = [
      'currency',
      'shipping_cost_dhaka',
      'shipping_cost_outside',
      'tax',
      'delivery_time_dhaka',
      'delivery_time_outside',
      'note',
    ];
}

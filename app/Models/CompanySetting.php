<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanySetting extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'mobile1',
        'mobile2',
        'email',
        'about',
        'about_footer',
        'facebook',
        'twitter',
        'instagram',
        'whatsapp',
        'location',
        'logo',
        'footer_logo',
    ];
}

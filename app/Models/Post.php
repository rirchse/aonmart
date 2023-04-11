<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Post extends Model
{
    use HasFactory;

  protected $fillable = [
    'name',
    'image',
    'details',
    'seo_key_word',
    'category_id',
    'status',
  ];

  public function blogTags(): BelongsToMany
  {
    return $this->belongsToMany(BlogTag::class, 'post_blog_tags', 'post_id');
  }

  public function category(): BelongsTo
  {
    return $this->belongsTo(PostCategory::class);
  }
}

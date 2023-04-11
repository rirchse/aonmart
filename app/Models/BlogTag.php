<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class BlogTag extends Model
{
    use HasFactory;

  protected $fillable = [
    'name'
  ];

  public function posts(): BelongsToMany
  {
    return $this->belongsToMany(Post::class, 'post_blog_tags', 'blog_tag_id');
  }
}

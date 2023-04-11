<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostBlogTagsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('post_blog_tags', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('post_id');
      $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade');
      $table->unsignedBigInteger('blog_tag_id');
      $table->foreign('blog_tag_id')->references('id')->on('blog_tags')->onDelete('cascade');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('post_blog_tags');
  }
}

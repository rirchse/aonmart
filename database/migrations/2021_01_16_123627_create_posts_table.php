<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('posts', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->string('image')->nullable();
      $table->longText('details')->nullable();
      $table->string('seo_key_word')->nullable();
      $table->unsignedBigInteger('category_id');
      $table->foreign('category_id')->references('id')->on('post_categories')->onDelete('cascade');
      $table->boolean('status')->default(true);
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
    Schema::dropIfExists('posts');
  }
}

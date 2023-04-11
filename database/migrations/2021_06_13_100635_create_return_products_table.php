<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReturnProductsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('return_products', function (Blueprint $table) {
      $table->id();
      $table->integer('total_qty')->nullable();
      $table->string('reason')->nullable();
      $table->integer('return_amount')->default(0);
      $table->integer('status')->default(0);
      $table->foreignId('order_id')->nullable()->constrained('orders')->cascadeOnDelete();
      $table->foreignId('sale_id')->nullable()->constrained('sales')->cascadeOnDelete();
      $table->foreignId('purchase_id')->nullable()->constrained('purchases')->cascadeOnDelete();
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
    Schema::dropIfExists('return_products');
  }
}

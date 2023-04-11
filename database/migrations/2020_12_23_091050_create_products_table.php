<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('barcode')->nullable();
            $table->string('name');
            $table->longText('short_description')->nullable();
            $table->longText('full_description')->nullable();
            $table->integer('quantity')->nullable();
            $table->foreignId('unit_id')->nullable()->constrained();
            $table->integer('regular_price');
            $table->integer('sell_price')->nullable();
            $table->integer('discount')->nullable();
            $table->decimal('stock')->default(0);
            $table->decimal('stock_out')->default(0);
            $table->string('image')->nullable();
            $table->text('gallery')->nullable();
            $table->boolean('status')->default(true);
            $table->boolean('featured')->default(false);
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
        Schema::dropIfExists('products');
    }
}

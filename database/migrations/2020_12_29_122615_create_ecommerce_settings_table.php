<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEcommerceSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ecommerce_settings', function (Blueprint $table) {
            $table->id();
            $table->string('currency')->default('BDT');
            $table->integer('shipping_cost_dhaka')->default(50);
            $table->integer('shipping_cost_outside')->default(100);
            $table->integer('tax')->nullable();
            $table->integer('delivery_time_dhaka')->nullable();
            $table->integer('delivery_time_outside')->nullable();
            $table->text('note')->nullable();
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
        Schema::dropIfExists('ecommerce_settings');
    }
}

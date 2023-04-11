<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->nullable()->constrained()->onDelete('set null');
            $table->string('invoice_no', 255);
            $table->date('purchase_date');
            $table->foreignId('supplier_id')->references('id')->on('users');
            $table->longText('purchase_note')->nullable();
            $table->float('grand_total');
            $table->float('total_qty');
            $table->float('due_amount')->nullable();
            $table->boolean('status')->default(TRUE);
            $table->boolean('is_stocked')->default(FALSE);
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
        Schema::dropIfExists('purchases');
    }
}

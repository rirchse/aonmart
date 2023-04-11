<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScriptManagerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('script_manager', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment("Name of the script function.");
            $table->longText('description')->comment("Description of the script function.");
            $table->string('status')->comment("Note of the last execution status.");
            $table->boolean('is_executed')->default(false)->comment("Is the script executed?");
            $table->integer('execution_count')->default(0)->comment("Number of times the script is executed.");
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
        Schema::dropIfExists('script_manager');
    }
}

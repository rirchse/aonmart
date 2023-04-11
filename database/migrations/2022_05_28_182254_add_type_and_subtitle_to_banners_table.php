<?php

use App\Models\Banner;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('banners', function (Blueprint $table) {
            $table->string('type')
                ->default(Banner::TYPE_BANNER)
                ->comment(json_encode(array_keys(Banner::TYPES)));
            $table->string('subtitle')
                ->nullable();
        });
    }

    public function down()
    {
        Schema::table('banners', function (Blueprint $table) {
            $table->dropColumn(['type', 'subtitle']);
        });
    }
};

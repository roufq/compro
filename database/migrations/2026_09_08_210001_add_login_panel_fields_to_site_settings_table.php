<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->string('hero_badge_text')->nullable()->after('hero_description');
            $table->string('hero_tile_1_path')->nullable()->after('hero_badge_text');
            $table->string('hero_tile_2_path')->nullable()->after('hero_tile_1_path');
            $table->string('hero_tile_3_path')->nullable()->after('hero_tile_2_path');
        });
    }

    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn(['hero_badge_text', 'hero_tile_1_path', 'hero_tile_2_path', 'hero_tile_3_path']);
        });
    }
};

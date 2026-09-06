<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->string('hero_image_path')->nullable();
            $table->string('about_title')->nullable();
            $table->text('about_description')->nullable();
            $table->string('map_query')->nullable();
            $table->json('clients')->nullable();
            $table->json('original_ips')->nullable();
            $table->json('team_members')->nullable();
            $table->json('products')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn(['hero_image_path', 'about_title', 'about_description', 'map_query', 'clients', 'original_ips', 'team_members', 'products']);
        });
    }
};

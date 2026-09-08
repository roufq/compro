<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('original_ips', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug', 180)->unique();
            $table->string('description', 1000)->nullable();
            $table->string('image_url', 2048)->nullable();
            $table->string('url', 2048)->nullable();
            $table->text('details')->nullable();
            $table->longText('gallery_urls')->nullable();
            $table->longText('video_urls')->nullable();
            $table->unsignedInteger('order')->default(0)->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('original_ips');
    }
};

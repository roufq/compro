<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->string('image_path')->nullable()->after('image_url');
        });

        DB::table('clients')
            ->whereNotNull('image_url')
            ->orderBy('id')
            ->eachById(function (object $client): void {
                $path = parse_url($client->image_url, PHP_URL_PATH);

                if (! is_string($path) || ! Str::startsWith($path, '/storage/clients/')) {
                    return;
                }

                DB::table('clients')->where('id', $client->id)->update([
                    'image_path' => Str::after($path, '/storage/'),
                    'image_url' => null,
                ]);
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('clients')
            ->whereNotNull('image_path')
            ->whereNull('image_url')
            ->orderBy('id')
            ->eachById(function (object $client): void {
                DB::table('clients')->where('id', $client->id)->update([
                    'image_url' => rtrim((string) config('app.url'), '/').'/storage/'.$client->image_path,
                ]);
            });

        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn('image_path');
        });
    }
};

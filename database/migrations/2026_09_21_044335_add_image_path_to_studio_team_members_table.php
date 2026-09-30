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
        Schema::table('studio_team_members', function (Blueprint $table) {
            $table->string('image_path')->nullable()->after('image_url');
        });

        DB::table('studio_team_members')
            ->whereNotNull('image_url')
            ->orderBy('id')
            ->eachById(function (object $member): void {
                $path = parse_url($member->image_url, PHP_URL_PATH);

                if (! is_string($path) || ! Str::startsWith($path, '/storage/team-members/')) {
                    return;
                }

                DB::table('studio_team_members')->where('id', $member->id)->update([
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
        DB::table('studio_team_members')
            ->whereNotNull('image_path')
            ->whereNull('image_url')
            ->orderBy('id')
            ->eachById(function (object $member): void {
                DB::table('studio_team_members')->where('id', $member->id)->update([
                    'image_url' => rtrim((string) config('app.url'), '/').'/storage/'.$member->image_path,
                ]);
            });

        Schema::table('studio_team_members', function (Blueprint $table) {
            $table->dropColumn('image_path');
        });
    }
};

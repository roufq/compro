<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        $settings = DB::table('site_settings')->first();

        if (! $settings) {
            return;
        }

        $now = now();

        $clients = json_decode($settings->clients ?? '[]', true) ?: [];
        foreach ($clients as $index => $client) {
            DB::table('clients')->insert([
                'name' => $client['name'] ?? '',
                'image_url' => $client['image_url'] ?? null,
                'order' => $index,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        $teamMembers = json_decode($settings->team_members ?? '[]', true) ?: [];
        foreach ($teamMembers as $index => $member) {
            DB::table('studio_team_members')->insert([
                'name' => $member['name'] ?? '',
                'role' => $member['role'] ?? '',
                'image_url' => $member['image_url'] ?? null,
                'order' => $index,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        $products = json_decode($settings->products ?? '[]', true) ?: [];
        foreach ($products as $index => $product) {
            DB::table('products')->insert([
                'name' => $product['name'] ?? '',
                'description' => $product['description'] ?? null,
                'url' => $product['url'] ?? '',
                'order' => $index,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        $originalIps = json_decode($settings->original_ips ?? '[]', true) ?: [];
        $usedSlugs = [];
        foreach ($originalIps as $index => $ip) {
            $slug = $ip['slug'] ?? null;

            if (! $slug) {
                $base = Str::slug(Str::limit($ip['name'] ?? 'original-ip', 150, '')) ?: 'original-ip';
                $slug = $base;
                $suffix = 2;
                while (in_array($slug, $usedSlugs, true)) {
                    $slug = $base.'-'.$suffix++;
                }
            }
            $usedSlugs[] = $slug;

            DB::table('original_ips')->insert([
                'name' => $ip['name'] ?? '',
                'slug' => $slug,
                'description' => $ip['description'] ?? null,
                'image_url' => $ip['image_url'] ?? null,
                'url' => $ip['url'] ?? null,
                'details' => $ip['details'] ?? null,
                'gallery_urls' => $ip['gallery_urls'] ?? null,
                'video_urls' => $ip['video_urls'] ?? null,
                'order' => $index,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    public function down(): void
    {
        DB::table('clients')->truncate();
        DB::table('studio_team_members')->truncate();
        DB::table('products')->truncate();
        DB::table('original_ips')->truncate();
    }
};

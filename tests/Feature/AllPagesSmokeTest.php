<?php

use App\Models\Client;
use App\Models\OriginalIp;
use App\Models\Portfolio;
use App\Models\Product;
use App\Models\Service;
use App\Models\TeamMember;
use App\Models\Testimonial;
use App\Models\User;

test('every public and admin page responds without a server error', function () {
    // Seed one row of everything so pages with per-item rows/modals render fully.
    Client::create(['name' => 'Klien Cek']);
    TeamMember::create(['name' => 'Anggota Cek', 'role' => 'Peran']);
    Product::create(['name' => 'Produk Cek', 'url' => 'https://example.com']);
    $ip = OriginalIp::create(['name' => 'IP Cek', 'slug' => 'ip-cek']);
    Portfolio::create(['title' => 'Karya Cek', 'category' => 'branding', 'type' => 'gambar']);
    Service::create(['title' => 'Layanan Cek', 'description' => 'Deskripsi']);
    Testimonial::create(['name' => 'Testi Cek', 'quote' => 'Bagus sekali']);

    $admin = User::factory()->create();

    $publicRoutes = [
        route('home'),
        route('original-ip.show', $ip->slug),
    ];

    $adminGetRoutes = [
        'admin.akun.edit', 'admin.identitas', 'admin.hero', 'admin.kontak',
        'admin.klien.index', 'admin.original-ip.index', 'admin.tim.index', 'admin.produk.index',
        'admin.layanan.index', 'admin.portofolio.index', 'admin.testimoni.index',
    ];

    $results = [];

    foreach ($publicRoutes as $url) {
        $results[$url] = $this->get($url)->getStatusCode();
    }

    foreach ($adminGetRoutes as $name) {
        $results[$name] = $this->actingAs($admin)->get(route($name))->getStatusCode();
    }

    $failures = array_filter($results, fn (int $code): bool => $code >= 400);

    expect($failures)->toBe([]);
});

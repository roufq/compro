<?php

use App\Models\Portfolio;
use App\Models\SiteSetting;
use Database\Seeders\HomepageContentSeeder;
use Database\Seeders\OriginalIpDemoSeeder;

test('demo seeder fills all three IP detail pages with local images and reference videos', function () {
    $this->seed(HomepageContentSeeder::class);
    $this->seed(OriginalIpDemoSeeder::class);

    $items = SiteSetting::current()->original_ips;
    expect($items)->toHaveCount(3);

    foreach ($items as $ip) {
        $photos = explode("\n", $ip['gallery_urls']);
        expect($photos)->toHaveCount(2);
        foreach ($photos as $photo) {
            $path = public_path(ltrim(parse_url($photo, PHP_URL_PATH), '/'));
            expect(is_file($path))->toBeTrue();
            expect(getimagesize($path))->not->toBeFalse();
        }

        expect(Portfolio::youtubeIdFromUrl($ip['video_urls']))->not->toBeNull();
        $this->get(route('original-ip.show', $ip['slug']))->assertSuccessful()
            ->assertSeeText($ip['name'])
            ->assertSee($ip['image_url'])
            ->assertSee($photos[1])
            ->assertSeeText('Galeri Foto')
            ->assertSee('youtube-nocookie.com/embed/');
    }

    expect($items[0]['details'])->toContain('bahasa Indonesia', 'bukan produksi Teman Ceria')
        ->and($items[1]['details'])->toContain('English', 'not a Happy Friends')
        ->and($items[2]['details'])->toContain('Jojo, Acong, dan Sitorus', 'bukan produksi JAS');
});

test('demo seeder preserves custom content and does not recreate deleted IPs', function () {
    SiteSetting::current()->update(['original_ips' => [
        ['name' => 'Teman Ceria', 'slug' => 'alamat-tetap', 'description' => 'Deskripsi milik pengguna', 'details' => 'Cerita asli.', 'image_url' => 'https://example.com/asli.png'],
        ['name' => 'IP Pengguna', 'details' => 'Jangan diubah.'],
    ]]);
    $this->seed(OriginalIpDemoSeeder::class);
    $firstRun = SiteSetting::current()->original_ips;
    $this->seed(OriginalIpDemoSeeder::class);

    expect(SiteSetting::current()->original_ips)->toBe($firstRun)->toHaveCount(2)
        ->and($firstRun[0]['slug'])->toBe('alamat-tetap')
        ->and($firstRun[0]['description'])->toBe('Deskripsi milik pengguna')
        ->and($firstRun[0]['details'])->toBe('Cerita asli.')
        ->and($firstRun[0]['image_url'])->toBe('https://example.com/asli.png')
        ->and($firstRun[1]['details'])->toBe('Jangan diubah.');
});

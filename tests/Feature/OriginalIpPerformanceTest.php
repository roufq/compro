<?php

use App\Models\SiteSetting;

test('demo optimization updates only matching image URLs and keeps originals', function () {
    $originalUrl = asset('images/original-ip-demo/teman-ceria.png');
    $webpUrl = asset('images/original-ip-demo/teman-ceria.webp');
    SiteSetting::current()->update(['original_ips' => [[
        'name' => 'Teman Ceria', 'slug' => 'alamat-tetap', 'details' => 'Cerita tetap.',
        'image_url' => $originalUrl,
        'gallery_urls' => $originalUrl."\nhttps://example.com/custom.png",
    ]]]);

    $sourceHash = hash_file('sha256', public_path('images/original-ip-demo/teman-ceria.png'));
    $this->artisan('app:optimize-original-ip-demo-images')->assertSuccessful();
    $items = SiteSetting::current()->original_ips;
    expect($items[0]['image_url'])->toBe($webpUrl)
        ->and($items[0]['gallery_urls'])->toBe($webpUrl."\nhttps://example.com/custom.png")
        ->and($items[0]['slug'])->toBe('alamat-tetap')
        ->and($items[0]['details'])->toBe('Cerita tetap.')
        ->and(hash_file('sha256', public_path('images/original-ip-demo/teman-ceria.png')))->toBe($sourceHash);

    $this->artisan('app:optimize-original-ip-demo-images')->assertSuccessful();
    expect(SiteSetting::current()->original_ips)->toBe($items);

    foreach (glob(public_path('images/original-ip-demo/*.webp')) as $path) {
        $dimensions = getimagesize($path);
        expect($dimensions[0])->toBeLessThanOrEqual(1200)
            ->and($dimensions['mime'])->toBe('image/webp')
            ->and(filesize($path))->toBeLessThan(200000);
    }
});

test('IP page defers the YouTube player until the visitor clicks play', function () {
    SiteSetting::current()->update(['original_ips' => [[
        'name' => 'Teman Ceria', 'video_urls' => 'https://youtu.be/fN1Cyr0ZK9M',
    ]]]);

    $this->get(route('original-ip.show', 'teman-ceria'))->assertSuccessful()
        ->assertDontSee('<iframe', escape: false)
        ->assertSee('data-video-src="https://www.youtube-nocookie.com/embed/fN1Cyr0ZK9M"', escape: false)
        ->assertSeeText('Putar video')
        ->assertSee('https://img.youtube.com/vi/fN1Cyr0ZK9M/hqdefault.jpg')
        ->assertSee('loading="lazy"', escape: false);
});

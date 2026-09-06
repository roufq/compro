<?php

use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

test('existing IP cards open their own public detail pages', function () {
    SiteSetting::current()->update(['original_ips' => [
        ['name' => 'Teman Ceria', 'description' => 'Original IP'],
        ['name' => 'Happy Friends', 'description' => 'Original IP'],
        ['name' => 'JAS — Jojo Acong Sitorus', 'description' => 'Original IP'],
    ]]);

    foreach (SiteSetting::current()->originalIpItems() as $ip) {
        $url = route('original-ip.show', $ip['slug']);
        $this->get('/')->assertSuccessful()->assertSee('href="'.$url.'"', escape: false);
        $this->get($url)->assertSuccessful()->assertSeeText('Tentang '.$ip['name'])
            ->assertSeeText('Informasi lengkap akan segera tersedia.')
            ->assertSee(route('home').'#our-ip');
    }
});

test('admin can save IP details photos and playable videos', function () {
    Storage::fake('public');
    $this->actingAs(User::factory()->create())
        ->put(route('admin.pengaturan.update'), [
            'company_name' => 'Studio',
            'original_ips' => [[
                'name' => 'Teman Ceria',
                'description' => 'Petualangan penuh persahabatan.',
                'details' => "Cerita dan karakter Teman Ceria.\nInformasi produksi.",
                'image_url' => 'https://example.com/cover.jpg',
                'gallery_urls' => "https://example.com/photo.jpg\n",
                'photos' => [UploadedFile::fake()->image('friend.jpg')],
                'video_urls' => "https://youtu.be/dQw4w9WgXcQ\nhttps://www.youtube.com/watch?v=9bZkp7q19f0",
            ]],
        ])->assertSessionHasNoErrors()->assertRedirect();

    $ip = SiteSetting::current()->original_ips[0];
    expect($ip['slug'])->toBe('teman-ceria')
        ->and($ip)->not->toHaveKey('photos');
    expect(Storage::disk('public')->allFiles('original-ips'))->toHaveCount(1);
    $uploadedUrl = asset('storage/'.Storage::disk('public')->allFiles('original-ips')[0]);

    $this->get(route('original-ip.show', $ip['slug']))->assertSuccessful()
        ->assertSeeText('Cerita dan karakter Teman Ceria.')
        ->assertSee('https://example.com/cover.jpg')
        ->assertSee('https://example.com/photo.jpg')
        ->assertSee($uploadedUrl)
        ->assertSee('https://www.youtube-nocookie.com/embed/dQw4w9WgXcQ')
        ->assertSee('https://www.youtube-nocookie.com/embed/9bZkp7q19f0');

    $this->get(route('admin.original-ip'))->assertSuccessful()
        ->assertSeeText('Informasi Lengkap')->assertSeeText('Tambah Foto dari Perangkat')
        ->assertSee('original_ips[0][photos][]', escape: false)
        ->assertSee($uploadedUrl)->assertSeeText('Lihat Halaman Detail');
});

test('saved IP addresses survive reordering and renaming and media can be cleared', function () {
    $this->actingAs(User::factory()->create());
    $this->put(route('admin.pengaturan.update'), ['company_name' => 'Studio', 'original_ips' => [
        ['name' => 'Teman Ceria', 'gallery_urls' => 'https://example.com/photo.jpg', 'video_urls' => 'https://youtu.be/dQw4w9WgXcQ'],
        ['name' => 'Happy Friends'],
    ]])->assertSessionHasNoErrors();
    $items = SiteSetting::current()->original_ips;
    $items[0]['name'] = 'Teman Ceria Baru';
    $items[0]['gallery_urls'] = '';
    $items[0]['video_urls'] = '';
    $this->put(route('admin.pengaturan.update'), ['company_name' => 'Studio', 'original_ips' => array_reverse($items)])
        ->assertSessionHasNoErrors();

    $this->get(route('original-ip.show', 'teman-ceria'))->assertSuccessful()
        ->assertSeeText('Teman Ceria Baru')->assertDontSee('https://example.com/photo.jpg')
        ->assertDontSee('youtube-nocookie.com/embed');
});

test('duplicate IP names get different addresses and unknown IPs return 404', function () {
    $this->actingAs(User::factory()->create())->put(route('admin.pengaturan.update'), [
        'company_name' => 'Studio', 'original_ips' => [['name' => 'Teman Ceria'], ['name' => 'Teman Ceria']],
    ])->assertSessionHasNoErrors();
    expect(array_column(SiteSetting::current()->original_ips, 'slug'))->toBe(['teman-ceria', 'teman-ceria-2']);
    $this->get(route('original-ip.show', 'teman-ceria-2'))->assertSuccessful();
    $this->get(route('original-ip.show', 'tidak-ada'))->assertNotFound();
});

test('IP media rejects unsafe URLs unsupported videos and non-image uploads', function (array $fields, string $error) {
    $this->actingAs(User::factory()->create())->put(route('admin.pengaturan.update'), [
        'company_name' => 'Studio', 'original_ips' => [array_merge(['name' => 'Teman Ceria'], $fields)],
    ])->assertSessionHasErrors('original_ips.0.'.$error);
})->with([
    [['gallery_urls' => 'javascript:alert(1)'], 'gallery_urls'],
    [['gallery_urls' => ['https://example.com/photo.jpg']], 'gallery_urls'],
    [['video_urls' => 'https://example.com/watch?v=dQw4w9WgXcQ'], 'video_urls'],
    [['video_urls' => 'https://youtu.be/not-valid'], 'video_urls'],
    [['slug' => '../secret'], 'slug'],
    fn () => [['photos' => [UploadedFile::fake()->create('bad.pdf', 10, 'application/pdf')]], 'photos.0'],
]);

test('duplicate explicit IP slugs are rejected', function () {
    $this->actingAs(User::factory()->create())->put(route('admin.pengaturan.update'), [
        'company_name' => 'Studio', 'original_ips' => [
            ['name' => 'A', 'slug' => 'same'], ['name' => 'B', 'slug' => 'same'],
        ],
    ])->assertSessionHasErrors('original_ips.0.slug');
});

test('IP detail text is escaped and other IP content stays separate', function () {
    SiteSetting::current()->update(['original_ips' => [
        ['name' => 'Teman Ceria', 'details' => '<script>alert(1)</script>'],
        ['name' => 'Happy Friends', 'details' => 'Rahasia Happy Friends'],
    ]]);
    $this->get(route('original-ip.show', 'teman-ceria'))->assertSuccessful()
        ->assertSee('&lt;script&gt;alert(1)&lt;/script&gt;', escape: false)
        ->assertDontSee('<script>alert(1)</script>', escape: false)
        ->assertDontSeeText('Rahasia Happy Friends');
});

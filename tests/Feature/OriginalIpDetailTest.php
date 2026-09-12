<?php

use App\Models\OriginalIp;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

test('IP cards without a YouTube channel link are not clickable on the homepage', function () {
    OriginalIp::create(['name' => 'Teman Ceria', 'slug' => 'teman-ceria', 'description' => 'Original IP']);
    OriginalIp::create(['name' => 'Happy Friends', 'slug' => 'happy-friends', 'description' => 'Original IP']);

    $this->get('/')->assertSuccessful()
        ->assertDontSee('ip-card" href', escape: false)
        ->assertSee('ip-card--disabled', escape: false);
});

test('IP cards with a YouTube channel link open it in a new tab from the homepage', function () {
    OriginalIp::create([
        'name' => 'Teman Ceria', 'slug' => 'teman-ceria', 'description' => 'Original IP',
        'url' => 'https://www.youtube.com/@temanceria',
    ]);

    $this->get('/')->assertSuccessful()
        ->assertSee('href="https://www.youtube.com/@temanceria" target="_blank"', escape: false);
});

test('the original IP detail page still renders directly by URL', function () {
    $ip = OriginalIp::create(['name' => 'Teman Ceria', 'slug' => 'teman-ceria', 'description' => 'Original IP']);

    $this->get(route('original-ip.show', $ip->slug))->assertSuccessful()
        ->assertSeeText('Tentang '.$ip->name)
        ->assertSeeText('Informasi lengkap akan segera tersedia.')
        ->assertSee(route('home').'#our-ip');
});

test('admin can save IP details photos and playable videos', function () {
    Storage::fake('public');
    $this->actingAs(User::factory()->create())
        ->post(route('admin.original-ip.store'), [
            'name' => 'Teman Ceria',
            'description' => 'Petualangan penuh persahabatan.',
            'details' => "Cerita dan karakter Teman Ceria.\nInformasi produksi.",
            'image_url' => 'https://example.com/cover.jpg',
            'gallery_urls' => "https://example.com/photo.jpg\n",
            'photos' => [UploadedFile::fake()->image('friend.jpg')],
            'video_urls' => "https://youtu.be/dQw4w9WgXcQ\nhttps://www.youtube.com/watch?v=9bZkp7q19f0",
        ])->assertSessionHasNoErrors()->assertRedirect();

    $ip = OriginalIp::firstWhere('name', 'Teman Ceria');
    expect($ip->slug)->toBe('teman-ceria');
    expect(Storage::disk('public')->allFiles('original-ips'))->toHaveCount(1);
    $uploadedUrl = asset('storage/'.Storage::disk('public')->allFiles('original-ips')[0]);

    $this->get(route('original-ip.show', $ip->slug))->assertSuccessful()
        ->assertSeeText('Cerita dan karakter Teman Ceria.')
        ->assertSee('https://example.com/cover.jpg')
        ->assertSee('https://example.com/photo.jpg')
        ->assertSee($uploadedUrl)
        ->assertSee('https://www.youtube-nocookie.com/embed/dQw4w9WgXcQ')
        ->assertSee('https://www.youtube-nocookie.com/embed/9bZkp7q19f0');

    $this->get(route('admin.original-ip.index'))->assertSuccessful()
        ->assertSeeText('Original IP')
        ->assertSeeText('Teman Ceria');
});

test('admin original IP forms hide detail and gallery controls', function () {
    $admin = User::factory()->create();
    OriginalIp::create(['name' => 'Teman Ceria', 'slug' => 'teman-ceria']);

    $this->actingAs($admin)
        ->get(route('admin.original-ip.index'))
        ->assertSuccessful()
        ->assertDontSee('name="details"', escape: false)
        ->assertDontSee('name="gallery_urls"', escape: false)
        ->assertDontSee('name="photos[]"', escape: false)
        ->assertDontSee('name="video_urls"', escape: false)
        ->assertDontSeeText('Lihat Halaman Detail');
});

test('renaming an original IP keeps its slug and public link stable', function () {
    $admin = User::factory()->create();
    $ip = OriginalIp::create(['name' => 'Teman Ceria', 'slug' => 'teman-ceria']);

    $this->actingAs($admin)->put(route('admin.original-ip.update', $ip), [
        'name' => 'Teman Ceria Baru',
        'gallery_urls' => '',
        'video_urls' => '',
    ])->assertSessionHasNoErrors();

    expect($ip->fresh()->slug)->toBe('teman-ceria');

    $this->get(route('original-ip.show', 'teman-ceria'))->assertSuccessful()
        ->assertSeeText('Teman Ceria Baru');
});

test('duplicate IP names get different slugs and unknown IPs return 404', function () {
    $admin = User::factory()->create();
    $this->actingAs($admin)->post(route('admin.original-ip.store'), ['name' => 'Teman Ceria'])->assertSessionHasNoErrors();
    $this->actingAs($admin)->post(route('admin.original-ip.store'), ['name' => 'Teman Ceria'])->assertSessionHasNoErrors();

    expect(OriginalIp::orderBy('id')->pluck('slug')->all())->toBe(['teman-ceria', 'teman-ceria-2']);
    $this->get(route('original-ip.show', 'teman-ceria-2'))->assertSuccessful();
    $this->get(route('original-ip.show', 'tidak-ada'))->assertNotFound();
});

test('IP media rejects unsafe URLs unsupported videos and non-image uploads', function (array $fields, string $error) {
    $this->actingAs(User::factory()->create())
        ->post(route('admin.original-ip.store'), array_merge(['name' => 'Teman Ceria'], $fields))
        ->assertSessionHasErrors($error);
})->with([
    [['gallery_urls' => 'javascript:alert(1)'], 'gallery_urls'],
    [['video_urls' => 'https://example.com/watch?v=dQw4w9WgXcQ'], 'video_urls'],
    [['video_urls' => 'https://youtu.be/not-valid'], 'video_urls'],
    fn () => [['photos' => [UploadedFile::fake()->create('bad.pdf', 10, 'application/pdf')]], 'photos.0'],
]);

test('IP detail text is escaped and other IP content stays separate', function () {
    OriginalIp::create(['name' => 'Teman Ceria', 'slug' => 'teman-ceria', 'details' => '<script>alert(1)</script>']);
    OriginalIp::create(['name' => 'Happy Friends', 'slug' => 'happy-friends', 'details' => 'Rahasia Happy Friends']);

    $this->get(route('original-ip.show', 'teman-ceria'))->assertSuccessful()
        ->assertSee('&lt;script&gt;alert(1)&lt;/script&gt;', escape: false)
        ->assertDontSee('<script>alert(1)</script>', escape: false)
        ->assertDontSeeText('Rahasia Happy Friends');
});

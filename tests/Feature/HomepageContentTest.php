<?php

use App\Models\Client;
use App\Models\OriginalIp;
use App\Models\Product;
use App\Models\SiteSetting;
use App\Models\TeamMember;
use App\Models\User;
use Database\Seeders\HomepageContentSeeder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

test('admin can save and render the new homepage sections', function () {
    $admin = User::factory()->create();

    $this->actingAs($admin)
        ->put(route('admin.pengaturan.update'), [
            'company_name' => 'Studio Baru',
            'about_title' => 'Tentang Studio Baru',
            'about_description' => 'Cerita studio kami.',
            'phone' => '0812-3456-7890',
            'map_query' => 'Kantor Studio, Yogyakarta',
        ])->assertSessionHasNoErrors()->assertRedirect();

    $this->actingAs($admin)->post(route('admin.klien.store'), [
        'name' => 'Klien Utama', 'image_url' => 'https://example.com/client.png',
    ])->assertSessionHasNoErrors();

    $this->actingAs($admin)->post(route('admin.original-ip.store'), [
        'name' => 'IP Baru', 'description' => 'Kisah baru', 'url' => 'https://example.com/ip',
    ])->assertSessionHasNoErrors();

    $this->actingAs($admin)->post(route('admin.tim.store'), [
        'name' => 'Sinta', 'role' => 'Art Director', 'image_url' => 'https://example.com/sinta.jpg',
    ])->assertSessionHasNoErrors();

    $this->actingAs($admin)->post(route('admin.produk.store'), [
        'name' => 'Paket Storyboard', 'description' => 'Aset siap pakai', 'url' => 'https://scalev.id/produk/studio',
    ])->assertSessionHasNoErrors();

    expect(TeamMember::first()->name)->toBe('Sinta');

    $this->get('/')
        ->assertSuccessful()
        ->assertSeeText('Tentang Studio Baru')
        ->assertSeeText('Cerita studio kami.')
        ->assertSee('https://example.com/client.png')
        ->assertSee('.trusted-logo{display:flex;align-items:center;justify-content:center;flex-shrink:0;filter:none;opacity:1;}', escape: false)
        ->assertSeeText('IP Baru')
        ->assertSeeText('Sinta')
        ->assertSeeText('Art Director')
        ->assertSee('https://example.com/sinta.jpg')
        ->assertSee('aspect-ratio:9/16', escape: false)
        ->assertSeeText('Paket Storyboard')
        ->assertSee('https://scalev.id/produk/studio')
        ->assertSee('https://wa.me/6281234567890')
        ->assertSee(rawurlencode('Kantor Studio, Yogyakarta'));

    $this->get(route('admin.tim.index'))->assertSuccessful()
        ->assertSee('value="Sinta"', escape: false);
    $this->get(route('admin.produk.index'))->assertSuccessful()
        ->assertSee('Paket Storyboard');
});

test('deleting a client, product, team member or original IP removes it from the homepage', function () {
    $admin = User::factory()->create();
    $client = Client::create(['name' => 'Pertama']);
    Client::create(['name' => 'Kedua']);
    $member = TeamMember::create(['name' => 'Sinta', 'role' => 'Director']);

    $this->actingAs($admin)->delete(route('admin.klien.destroy', $client))->assertSessionHasNoErrors();
    $this->actingAs($admin)->delete(route('admin.tim.destroy', $member))->assertSessionHasNoErrors();

    $this->get('/')->assertSuccessful()->assertDontSeeText('Pertama')->assertSeeText('Kedua')->assertDontSeeText('Sinta');
});

test('homepage collection validation rejects unsafe or incomplete data', function (string $routeName, array $payload, string $error) {
    $this->actingAs(User::factory()->create())
        ->post(route($routeName), $payload)
        ->assertSessionHasErrors($error);
})->with([
    ['admin.produk.store', ['name' => 'Produk', 'url' => 'javascript:alert(1)'], 'url'],
    ['admin.produk.store', ['name' => 'Produk'], 'url'],
    ['admin.tim.store', ['name' => 'Anggota'], 'role'],
    ['admin.klien.store', ['name' => 'Klien', 'image_url' => 'data:text/html,bad'], 'image_url'],
    ['admin.original-ip.store', ['name' => 'IP', 'url' => 'ftp://example.com'], 'url'],
]);

test('hero image can be uploaded displayed and removed', function () {
    Storage::fake('public');
    $this->actingAs(User::factory()->create())
        ->put(route('admin.pengaturan.update'), [
            'company_name' => 'Studio',
            'hero_image' => UploadedFile::fake()->image('hero.jpg'),
        ])->assertSessionHasNoErrors();
    $settings = SiteSetting::current();
    Storage::disk('public')->assertExists($settings->hero_image_path);
    $this->get('/')->assertSuccessful()->assertSee($settings->hero_image_url);
    $this->put(route('admin.pengaturan.update'), ['company_name' => 'Studio', 'remove_hero_image' => 1])
        ->assertSessionHasNoErrors();
    expect($settings->refresh()->hero_image_path)->toBeNull();
    $this->get('/')->assertSuccessful()->assertSee('class="hero-boat"', escape: false);
});

test('homepage uses the stacked brand, uncropped hero video, and Indonesian navigation', function () {
    $settings = SiteSetting::current();
    $settings->update([
        'logo_path' => 'branding/logo.png',
        'logo_text_path' => 'branding/logo-text.png',
        'hero_video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
    ]);

    $this->get('/')
        ->assertSuccessful()
        ->assertSee('class="hero-brand reveal"', escape: false)
        ->assertSee('class="hero-brand-mark"', escape: false)
        ->assertSee('class="hero-brand-text"', escape: false)
        ->assertSee('.hero{padding:28px 0 0;}', escape: false)
        ->assertDontSee('class="logo-text-img"', escape: false)
        ->assertDontSee('class="logo-text"', escape: false)
        ->assertSee('hero-visual--video', escape: false)
        ->assertSee('.hero-video{position:absolute;inset:0;width:100%;height:100%;border:0;pointer-events:none;}', escape: false)
        ->assertSeeText('Tentang Kami')
        ->assertSeeText('Portofolio')
        ->assertSeeText('Klien')
        ->assertSeeText('IP Kami')
        ->assertSee('.nav-links{display:flex;gap:28px;margin-left:auto;', escape: false)
        ->assertSee('.nav-cta{margin-left:36px;', escape: false)
        ->assertSee('.nav-cta{margin-left:auto;}', escape: false)
        ->assertSee('<h1 class="hero-caption hero-caption--lowered" id="heroTitle">'.$settings->hero_title.'</h1>', escape: false)
        ->assertDontSee('<p class="hero-caption reveal">'.$settings->tagline.'</p>', escape: false)
        ->assertDontSeeText('Contact Us');
});

test('clients, original ips, and team member photos can be uploaded and stored', function () {
    Storage::fake('public');
    $admin = User::factory()->create();

    $this->actingAs($admin)->post(route('admin.klien.store'), [
        'name' => 'Klien Upload', 'logo' => UploadedFile::fake()->image('client.jpg'),
    ])->assertSessionHasNoErrors();

    $this->actingAs($admin)->post(route('admin.original-ip.store'), [
        'name' => 'IP Upload', 'cover' => UploadedFile::fake()->image('ip.jpg'),
    ])->assertSessionHasNoErrors();

    $this->actingAs($admin)->post(route('admin.tim.store'), [
        'name' => 'Anggota Upload', 'role' => 'Desainer', 'photo' => UploadedFile::fake()->image('member.jpg'),
    ])->assertSessionHasNoErrors();

    $client = Client::firstWhere('name', 'Klien Upload');
    $ip = OriginalIp::firstWhere('name', 'IP Upload');
    $member = TeamMember::firstWhere('name', 'Anggota Upload');

    expect($client->image_path)->not->toBeEmpty()
        ->and($client->image_url)->toBeNull()
        ->and($ip->image_path)->not->toBeEmpty()
        ->and($ip->image_url)->toBeNull()
        ->and($member->image_path)->not->toBeEmpty()
        ->and($member->image_url)->toBeNull();

    Storage::disk('public')->assertExists($client->image_path);
    Storage::disk('public')->assertExists($ip->image_path);
    Storage::disk('public')->assertExists($member->image_path);

    $this->get('/')->assertSuccessful()
        ->assertSee($client->logo_url)
        ->assertSee($member->photo_url);
});

test('uploaded team photos use the host that serves the homepage', function () {
    Storage::fake('public');

    $this->actingAs(User::factory()->create())
        ->post(route('admin.tim.store'), [
            'name' => 'Anggota Jaringan',
            'role' => 'Desainer',
            'photo' => UploadedFile::fake()->image('member-network.jpg'),
        ])->assertSessionHasNoErrors();

    $member = TeamMember::firstWhere('name', 'Anggota Jaringan');

    $this->get('http://192.168.18.77/')
        ->assertSuccessful()
        ->assertSee('http://192.168.18.77/storage/'.$member->image_path);
});

test('uploaded client logos use the host that serves the homepage', function () {
    Storage::fake('public');

    $this->actingAs(User::factory()->create())
        ->post(route('admin.klien.store'), [
            'name' => 'Klien Jaringan',
            'logo' => UploadedFile::fake()->image('client-network.jpg'),
        ])->assertSessionHasNoErrors();

    $client = Client::firstWhere('name', 'Klien Jaringan');

    $this->get('http://192.168.18.77/')
        ->assertSuccessful()
        ->assertSee('http://192.168.18.77/storage/'.$client->image_path);
});

test('uploaded photo rejects oversized or non-image files', function () {
    Storage::fake('public');
    $this->actingAs(User::factory()->create())
        ->post(route('admin.klien.store'), [
            'name' => 'Klien', 'logo' => UploadedFile::fake()->create('doc.pdf', 100, 'application/pdf'),
        ])->assertSessionHasErrors('logo');

    $this->actingAs(User::factory()->create())
        ->post(route('admin.tim.store'), [
            'name' => 'Anggota', 'role' => 'Desainer', 'photo' => UploadedFile::fake()->image('big.jpg')->size(3000),
        ])->assertSessionHasErrors('photo');
});

test('homepage media uploads enforce web optimized file size limits', function (
    string $method,
    string $routeName,
    string $field,
    int $sizeInKilobytes,
    array $payload,
    string $message,
) {
    Storage::fake('public');

    $payload[$field] = UploadedFile::fake()->image($field.'.png')->size($sizeInKilobytes);
    $response = $this->actingAs(User::factory()->create())
        ->{$method}(route($routeName), $payload);

    $response->assertSessionHasErrors([
        $field => $message,
    ]);
})->with([
    'site logo over 80 KB' => ['put', 'admin.pengaturan.update', 'logo', 81, ['company_name' => 'Studio'], 'Ukuran logo utama maksimal 80 KB.'],
    'hero image over 500 KB' => ['put', 'admin.pengaturan.update', 'hero_image', 501, ['company_name' => 'Studio'], 'Ukuran gambar hero maksimal 500 KB.'],
    'client logo over 60 KB' => ['post', 'admin.klien.store', 'logo', 61, ['name' => 'Klien'], 'Ukuran logo klien maksimal 60 KB.'],
    'team photo over 150 KB' => ['post', 'admin.tim.store', 'photo', 151, ['name' => 'Anggota', 'role' => 'Desainer'], 'Ukuran foto anggota tim maksimal 150 KB.'],
    'original IP cover over 250 KB' => ['post', 'admin.original-ip.store', 'cover', 251, ['name' => 'IP Baru'], 'Ukuran cover Original IP maksimal 250 KB.'],
    'testimonial photo over 150 KB' => ['post', 'admin.testimoni.store', 'avatar', 151, [
        'name' => 'Pelanggan',
        'quote' => 'Pelayanan sangat baik.',
    ], 'Ukuran foto testimoni maksimal 150 KB.'],
    'portfolio thumbnail over 250 KB' => ['post', 'admin.portofolio.store', 'image', 251, [
        'title' => 'Karya',
        'category' => 'branding',
        'youtube_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
    ], 'Ukuran thumbnail portofolio maksimal 250 KB.'],
]);

test('admin notifications and delete confirmations use SweetAlert', function () {
    $this->actingAs(User::factory()->create())
        ->withSession(['status' => 'Data berhasil disimpan.'])
        ->get(route('admin.klien.index'))
        ->assertSuccessful()
        ->assertSee('https://cdn.jsdelivr.net/npm/sweetalert2@11', escape: false)
        ->assertSee("title: 'Berhasil'", escape: false)
        ->assertSee("title: 'Hapus data ini?'", escape: false)
        ->assertDontSee("return confirm('Hapus klien ini?')", escape: false);
});

test('homepage escapes collection content and handles empty content', function () {
    Client::create(['name' => '<script>alert(1)</script>']);
    $this->get('/')->assertSuccessful()
        ->assertSee('&lt;script&gt;alert(1)&lt;/script&gt;', escape: false)
        ->assertDontSee('<script>alert(1)</script>', escape: false)
        ->assertSeeText('Produk digital akan segera tersedia.')
        ->assertDontSee('checkout/placeholder');
});

test('homepage seeding preserves existing content and is idempotent', function () {
    SiteSetting::current()->update(['company_name' => 'Nama Lama', 'about_title' => 'Judul Lama']);
    Client::query()->delete();

    $this->seed(HomepageContentSeeder::class);
    $this->seed(HomepageContentSeeder::class);

    expect(SiteSetting::current())
        ->company_name->toBe('Nama Lama')
        ->about_title->toBe('Judul Lama');
    expect(Product::count())->toBe(0);
    expect(OriginalIp::count())->toBe(3);
});

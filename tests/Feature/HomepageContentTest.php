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
        ->assertSeeText('IP Baru')
        ->assertSeeText('Sinta')
        ->assertSeeText('Art Director')
        ->assertSee('https://example.com/sinta.jpg')
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

    expect($client->image_url)->not->toBeEmpty()
        ->and($ip->image_url)->not->toBeEmpty()
        ->and($member->image_url)->not->toBeEmpty();

    Storage::disk('public')->assertExists(str_replace(asset('storage/'), '', $client->image_url));
    Storage::disk('public')->assertExists(str_replace(asset('storage/'), '', $ip->image_url));
    Storage::disk('public')->assertExists(str_replace(asset('storage/'), '', $member->image_url));

    $this->get('/')->assertSuccessful()
        ->assertSee($client->image_url)
        ->assertSee($member->image_url);
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

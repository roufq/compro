<?php

use App\Models\SiteSetting;
use App\Models\User;
use Database\Seeders\HomepageContentSeeder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

test('admin can save and render the new homepage sections', function () {
    $this->actingAs(User::factory()->create())
        ->put(route('admin.pengaturan.update'), [
            'company_name' => 'Studio Baru',
            'about_title' => 'Tentang Studio Baru',
            'about_description' => 'Cerita studio kami.',
            'phone' => '0812-3456-7890',
            'map_query' => 'Kantor Studio, Yogyakarta',
            'clients' => [['name' => 'Klien Utama', 'image_url' => 'https://example.com/client.png']],
            'original_ips' => [['name' => 'IP Baru', 'description' => 'Kisah baru', 'url' => 'https://example.com/ip']],
            'team_members' => [['name' => 'Sinta', 'role' => 'Art Director', 'image_url' => 'https://example.com/sinta.jpg']],
            'products' => [['name' => 'Paket Storyboard', 'description' => 'Aset siap pakai', 'url' => 'https://scalev.id/produk/studio']],
        ])->assertSessionHasNoErrors()->assertRedirect();

    expect(SiteSetting::current()->team_members[0]['name'])->toBe('Sinta');

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

    $this->get(route('admin.tim'))->assertSuccessful()
        ->assertSee('value="Sinta"', escape: false);
    $this->get(route('admin.produk'))->assertSuccessful()
        ->assertSee('value="Paket Storyboard"', escape: false);
});

test('admin can reorder and clear collections without affecting omitted sections', function () {
    SiteSetting::current()->update([
        'clients' => [['name' => 'Pertama'], ['name' => 'Kedua']],
        'team_members' => [['name' => 'Sinta', 'role' => 'Director']],
    ]);
    $this->actingAs(User::factory()->create())
        ->put(route('admin.pengaturan.update'), [
            'company_name' => 'Studio',
            'clients' => [3 => ['name' => 'Kedua'], 7 => ['name' => 'Pertama']],
        ])->assertSessionHasNoErrors();
    expect(SiteSetting::current()->clients)->toBe([['name' => 'Kedua'], ['name' => 'Pertama']]);

    $this->put(route('admin.pengaturan.update'), ['company_name' => 'Studio', 'clients' => ''])
        ->assertSessionHasNoErrors();
    expect(SiteSetting::current()->clients)->toBe([])
        ->and(SiteSetting::current()->team_members[0]['name'])->toBe('Sinta');
    $this->get('/')->assertSuccessful()->assertDontSeeText('Pertama')->assertSeeText('Sinta');
});

test('homepage collection validation rejects unsafe or incomplete data', function (string $section, array $item, string $error) {
    $this->actingAs(User::factory()->create())
        ->put(route('admin.pengaturan.update'), ['company_name' => 'Studio', $section => [$item]])
        ->assertSessionHasErrors($error);
})->with([
    ['products', ['name' => 'Produk', 'url' => 'javascript:alert(1)'], 'products.0.url'],
    ['products', ['name' => 'Produk'], 'products.0.url'],
    ['team_members', ['name' => 'Anggota'], 'team_members.0.role'],
    ['clients', ['name' => 'Klien', 'image_url' => 'data:text/html,bad'], 'clients.0.image_url'],
    ['original_ips', ['name' => 'IP', 'url' => 'ftp://example.com'], 'original_ips.0.url'],
    ['clients', ['name' => 'Klien', 'unexpected' => 'value'], 'clients.0'],
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
    $this->actingAs(User::factory()->create())
        ->put(route('admin.pengaturan.update'), [
            'company_name' => 'Studio',
            'clients' => [['name' => 'Klien Upload', 'photo' => UploadedFile::fake()->image('client.jpg')]],
            'original_ips' => [['name' => 'IP Upload', 'photo' => UploadedFile::fake()->image('ip.jpg')]],
            'team_members' => [['name' => 'Anggota Upload', 'role' => 'Desainer', 'photo' => UploadedFile::fake()->image('member.jpg')]],
        ])->assertSessionHasNoErrors();

    $settings = SiteSetting::current();
    expect($settings->clients[0]['image_url'])->not->toBeEmpty()
        ->and($settings->clients[0])->not->toHaveKey('photo')
        ->and($settings->original_ips[0]['image_url'])->not->toBeEmpty()
        ->and($settings->team_members[0]['image_url'])->not->toBeEmpty();

    Storage::disk('public')->assertExists(str_replace(asset('storage/'), '', $settings->clients[0]['image_url']));
    Storage::disk('public')->assertExists(str_replace(asset('storage/'), '', $settings->original_ips[0]['image_url']));
    Storage::disk('public')->assertExists(str_replace(asset('storage/'), '', $settings->team_members[0]['image_url']));

    $this->get('/')->assertSuccessful()
        ->assertSee($settings->clients[0]['image_url'])
        ->assertSee($settings->team_members[0]['image_url']);
});

test('uploaded photo rejects oversized or non-image files', function () {
    Storage::fake('public');
    $this->actingAs(User::factory()->create())
        ->put(route('admin.pengaturan.update'), [
            'company_name' => 'Studio',
            'clients' => [['name' => 'Klien', 'photo' => UploadedFile::fake()->create('doc.pdf', 100, 'application/pdf')]],
        ])->assertSessionHasErrors('clients.0.photo');

    $this->actingAs(User::factory()->create())
        ->put(route('admin.pengaturan.update'), [
            'company_name' => 'Studio',
            'team_members' => [['name' => 'Anggota', 'role' => 'Desainer', 'photo' => UploadedFile::fake()->image('big.jpg')->size(3000)]],
        ])->assertSessionHasErrors('team_members.0.photo');
});

test('homepage escapes collection content and handles empty content', function () {
    SiteSetting::current()->update(['clients' => [['name' => '<script>alert(1)</script>']]]);
    $this->get('/')->assertSuccessful()
        ->assertSee('&lt;script&gt;alert(1)&lt;/script&gt;', escape: false)
        ->assertDontSee('<script>alert(1)</script>', escape: false)
        ->assertSeeText('Produk digital akan segera tersedia.')
        ->assertDontSee('checkout/placeholder');
});

test('homepage seeding preserves existing content and intentionally empty collections', function () {
    SiteSetting::current()->update(['company_name' => 'Nama Lama', 'clients' => [], 'about_title' => 'Judul Lama']);
    $this->seed(HomepageContentSeeder::class);
    $this->seed(HomepageContentSeeder::class);
    expect(SiteSetting::current())
        ->company_name->toBe('Nama Lama')
        ->clients->toBe([])
        ->about_title->toBe('Judul Lama');
    expect(SiteSetting::current()->original_ips)->toHaveCount(3);
});

<?php

use App\Http\Controllers\PublicController;
use App\Models\Portfolio;
use App\Models\Service;
use App\Models\SiteSetting;
use App\Models\Testimonial;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

test('home uses the public controller', function () {
    $route = Route::getRoutes()->getByName('home');

    expect($route?->getActionName())->toBe(PublicController::class.'@index');
});

test('home renders company profile content from the database', function () {
    SiteSetting::current()->update([
        'company_name' => 'Kedubes Studio Indonesia',
        'tagline' => 'Studio kreatif AI',
        'hero_title' => 'Kreativitas tanpa batas',
        'hero_description' => 'Deskripsi halaman utama dari database.',
        'email' => 'halo@example.com',
    ]);
    Service::create([
        'title' => 'Produksi Visual AI',
        'description' => 'Layanan dinamis dari database.',
    ]);
    Portfolio::create([
        'title' => 'Video Kampanye',
        'category' => 'video',
        'type' => 'video',
        'youtube_url' => 'https://youtu.be/dQw4w9WgXcQ',
    ]);
    Testimonial::create([
        'name' => 'Klien Kedubes',
        'quote' => 'Hasilnya sangat memuaskan.',
    ]);

    $this->get('/')
        ->assertSuccessful()
        ->assertSeeText('Kedubes Studio Indonesia')
        ->assertSeeText('Kreativitas tanpa batas')
        ->assertSeeText('Produksi Visual AI')
        ->assertSeeText('Klien Kedubes')
        ->assertSee('Video Kampanye')
        ->assertSee('https://img.youtube.com/vi/dQw4w9WgXcQ/hqdefault.jpg')
        ->assertSee('data-portfolio-index="0"', escape: false)
        ->assertSee('object-fit:contain', escape: false)
        ->assertSee('class="lightbox-image"', escape: false)
        ->assertSee('nav.links{display:flex;gap:34px;align-items:center;background:transparent;}', escape: false)
        ->assertSee('nav.links .nav-cta,nav.links .nav-cta:hover{color:#fff;}', escape: false)
        ->assertSee('position:absolute;top:100%;left:0;width:100vw;height:calc(100vh - 71px);height:calc(100dvh - 71px);', escape: false)
        ->assertSee('visibility:hidden;pointer-events:none;transform:translateX(100%);', escape: false)
        ->assertSee('aria-controls="navLinks" aria-expanded="false"', escape: false)
        ->assertSee("document.body.classList.toggle('nav-open', isOpen);", escape: false)
        ->assertSee('.hero-mosaic{grid-template-columns:1fr;padding:0 28px;}', escape: false)
        ->assertSee('.hero-mosaic .tile.d,.hero-mosaic .tile.e,.hero-mosaic .tile.f{grid-column:1/-1;}', escape: false)
        ->assertSee('.hero-media-meta .hero-play{position:absolute;left:50%;top:50%;', escape: false)
        ->assertSee('transform:translate(-50%,-50%);', escape: false)
        ->assertSee('color:var(--violet-deep);opacity:1;background:rgba(255,255,255,.94);', escape: false)
        ->assertSee('.hero-media-meta{position:absolute;inset:0;z-index:2;display:block;}', escape: false)
        ->assertSee('font-weight:600;line-height:1;letter-spacing:.1em;text-transform:uppercase;color:#fff;', escape: false)
        ->assertSee('background:rgba(15,15,25,.78);backdrop-filter:blur(10px);', escape: false);
});

test('authenticated admin can render every content management page', function (string $routeName, string $heading) {
    $admin = User::factory()->create();

    $this->actingAs($admin)
        ->get(route($routeName))
        ->assertSuccessful()
        ->assertSeeText($heading);
})->with([
    ['admin.konten', 'Pengaturan Umum'],
    ['admin.layanan.index', 'Tambah Layanan Baru'],
    ['admin.portofolio.index', 'Tambah Karya Baru'],
    ['admin.testimoni.index', 'Tambah Testimoni'],
]);

test('admin layout uses the same uploaded logo as the welcome page', function () {
    Storage::fake('public');
    $admin = User::factory()->create();

    $this->actingAs($admin)
        ->put(route('admin.konten.update'), [
            'company_name' => 'Brand Logo Dinamis',
            'logo' => UploadedFile::fake()->image('brand-logo.png'),
        ])
        ->assertSessionHasNoErrors();

    $logoPath = SiteSetting::current()->logo_path;

    Storage::disk('public')->assertExists($logoPath);

    $this->get('/')
        ->assertSuccessful()
        ->assertSee(asset('storage/'.$logoPath));

    $this->get(route('admin.layanan.index'))
        ->assertSuccessful()
        ->assertSeeText('BRAND LOGO DINAMIS')
        ->assertSee(asset('storage/'.$logoPath))
        ->assertSee('rel="icon" href="'.asset('storage/'.$logoPath).'?v=', escape: false)
        ->assertSee('class="av-logo"', escape: false);
});

test('account menus use the same uploaded company logo', function () {
    Storage::fake('public');
    $admin = User::factory()->create();
    $logoPath = UploadedFile::fake()->image('logo-akun.png')->store('site', 'public');

    SiteSetting::current()->update(['logo_path' => $logoPath]);

    $this->actingAs($admin)
        ->get(route('profile.edit'))
        ->assertSuccessful()
        ->assertSee(asset('storage/'.$logoPath))
        ->assertSee('data-test="dynamic-app-brand"', escape: false)
        ->assertSee('data-test="company-logo-avatar"', escape: false)
        ->assertSee('object-contain', escape: false);
});

test('authentication layout replaces the Laravel icon with the uploaded company logo', function () {
    Storage::fake('public');
    $logoPath = UploadedFile::fake()->image('logo-login.png')->store('site', 'public');

    SiteSetting::current()->update(['logo_path' => $logoPath]);

    $this->get(route('login'))
        ->assertSuccessful()
        ->assertSee(asset('storage/'.$logoPath))
        ->assertSee('rel="icon" href="'.asset('storage/'.$logoPath).'?v=', escape: false)
        ->assertDontSee('href="/favicon.svg"', escape: false)
        ->assertSee('data-test="dynamic-app-logo-icon"', escape: false);
});

test('content saved from the admin dashboard appears on the welcome page', function () {
    $admin = User::factory()->create();

    $this->actingAs($admin)
        ->put(route('admin.konten.update'), [
            'company_name' => 'Kedubes Dashboard',
            'tagline' => 'Data terhubung',
            'hero_title' => 'Hero dari Dashboard',
            'hero_description' => 'Deskripsi yang disimpan melalui halaman admin.',
            'email' => 'halo@kedubes.test',
            'phone' => '08123456789',
            'address' => 'Jakarta, Indonesia',
            'instagram_url' => 'https://instagram.com/kedubes',
            'linkedin_url' => 'https://linkedin.com/company/kedubes',
            'youtube_url' => 'https://youtube.com/@kedubes',
        ])
        ->assertSessionHasNoErrors();

    $this->actingAs($admin)
        ->post(route('admin.layanan.store'), [
            'title' => 'Layanan dari Dashboard',
            'description' => 'Deskripsi layanan tersambung.',
            'order' => 1,
        ])
        ->assertSessionHasNoErrors();

    $this->actingAs($admin)
        ->post(route('admin.portofolio.store'), [
            'title' => 'Portofolio dari Dashboard',
            'description' => 'Deskripsi portofolio tersambung.',
            'category' => 'video',
            'type' => 'video',
            'youtube_url' => 'https://youtu.be/dQw4w9WgXcQ',
            'order' => 1,
        ])
        ->assertSessionHasNoErrors();

    $this->actingAs($admin)
        ->post(route('admin.testimoni.store'), [
            'name' => 'Klien dari Dashboard',
            'role' => 'Pemilik Brand',
            'quote' => 'Testimoni ini tersambung ke welcome.',
            'rating' => 5,
        ])
        ->assertSessionHasNoErrors();

    $this->get('/')
        ->assertSuccessful()
        ->assertSeeText('Kedubes Dashboard')
        ->assertSeeText('Hero dari Dashboard')
        ->assertSeeText('Layanan dari Dashboard')
        ->assertSee('Portofolio dari Dashboard')
        ->assertSeeText('Klien dari Dashboard');
});

test('hubungi kami button opens gmail compose with the email configured from the admin dashboard', function () {
    $admin = User::factory()->create();

    $this->actingAs($admin)
        ->get(route('admin.konten'))
        ->assertSuccessful()
        ->assertSeeText('Email Tombol Hubungi Kami');

    $this->actingAs($admin)
        ->put(route('admin.konten.update'), [
            'company_name' => 'Kedubes Dashboard',
            'email' => 'kontak@kedubes.test',
        ])
        ->assertSessionHasNoErrors();

    $this->get('/')
        ->assertSuccessful()
        ->assertSee('href="https://mail.google.com/mail/?view=cm&amp;fs=1&amp;to=kontak%40kedubes.test"', escape: false)
        ->assertSee('>kontak@kedubes.test</a>', escape: false)
        ->assertSee('target="_blank"', escape: false)
        ->assertSee('rel="noopener noreferrer"', escape: false)
        ->assertSeeText('Hubungi Kami');
});

test('admin can edit an existing portfolio from the portfolio gallery', function () {
    $admin = User::factory()->create();
    $portfolio = Portfolio::create([
        'title' => 'Judul Lama',
        'description' => 'Deskripsi lama.',
        'category' => 'video',
        'type' => 'video',
        'youtube_url' => 'https://youtu.be/dQw4w9WgXcQ',
        'order' => 2,
    ]);

    $this->actingAs($admin)
        ->get(route('admin.portofolio.index'))
        ->assertSuccessful()
        ->assertSee('data-test="edit-portfolio-'.$portfolio->id.'"', escape: false)
        ->assertSee('data-test="portfolio-edit-modal-'.$portfolio->id.'"', escape: false)
        ->assertSee('Media Saat Ini')
        ->assertSee($portfolio->thumbnail_url)
        ->assertSee(route('admin.portofolio.update', $portfolio));

    $this->actingAs($admin)
        ->put(route('admin.portofolio.update', $portfolio), [
            'title' => 'Judul Baru',
            'description' => 'Deskripsi baru.',
            'category' => 'kampanye',
            'type' => 'video',
            'youtube_url' => 'https://youtu.be/9bZkp7q19f0',
            'order' => 1,
        ])
        ->assertSessionHasNoErrors()
        ->assertSessionHas('status', 'Karya berhasil diperbarui.');

    expect($portfolio->refresh())
        ->title->toBe('Judul Baru')
        ->description->toBe('Deskripsi baru.')
        ->category->toBe('kampanye')
        ->youtube_url->toBe('https://youtu.be/9bZkp7q19f0')
        ->order->toBe(1);

    $this->get('/')
        ->assertSuccessful()
        ->assertSeeText('Judul Baru');
});

test('changing a video portfolio to an image requires an uploaded image', function () {
    $admin = User::factory()->create();
    $portfolio = Portfolio::create([
        'title' => 'Video Lama',
        'category' => 'video',
        'type' => 'video',
        'youtube_url' => 'https://youtu.be/dQw4w9WgXcQ',
    ]);

    $this->actingAs($admin)
        ->from(route('admin.portofolio.index'))
        ->put(route('admin.portofolio.update', $portfolio), [
            'title' => 'Menjadi Gambar',
            'category' => 'gambar',
            'type' => 'gambar',
            'order' => 0,
        ])
        ->assertRedirect(route('admin.portofolio.index'))
        ->assertSessionHasErrors('image');
});

test('portfolio edit modal displays the currently stored image', function () {
    Storage::fake('public');
    $admin = User::factory()->create();
    $imagePath = UploadedFile::fake()->image('gambar-awal.jpg')->store('portfolio', 'public');
    $portfolio = Portfolio::create([
        'title' => 'Karya Bergambar',
        'category' => 'branding',
        'type' => 'gambar',
        'image_path' => $imagePath,
    ]);

    $this->actingAs($admin)
        ->get(route('admin.portofolio.index'))
        ->assertSuccessful()
        ->assertSee('data-test="portfolio-edit-modal-'.$portfolio->id.'"', escape: false)
        ->assertSee('Gambar yang sedang digunakan')
        ->assertSee($portfolio->thumbnail_url)
        ->assertSee('data-original-src="'.$portfolio->thumbnail_url.'"', escape: false);
});

test('all admin routes require authentication', function () {
    $adminRoutes = collect(Route::getRoutes()->getRoutes())
        ->filter(fn ($route) => str_starts_with($route->uri(), 'admin'));

    expect($adminRoutes)->not->toBeEmpty();

    $adminRoutes->each(function ($route): void {
        expect($route->gatherMiddleware())->toContain('auth');
    });
});

test('the admin seeder creates one usable admin account', function () {
    $this->seed(DatabaseSeeder::class);
    $this->seed(DatabaseSeeder::class);

    $this->assertDatabaseCount('users', 1);
    $this->assertDatabaseHas('users', [
        'email' => 'admin@kedubesstudio.id',
    ]);
    $this->assertDatabaseCount('teams', 1);
    $this->assertDatabaseCount('team_members', 1);

    $admin = User::where('email', 'admin@kedubesstudio.id')->firstOrFail();

    expect(Hash::check('ganti-password-ini', $admin->password))->toBeTrue()
        ->and($admin->current_team_id)->not->toBeNull();
});

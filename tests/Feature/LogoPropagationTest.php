<?php

use App\Models\OriginalIp;
use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

test('uploaded logo propagates to favicon, homepage, and every branded page', function () {
    Storage::fake('public');
    $admin = User::factory()->create();
    OriginalIp::create(['name' => 'IP Cek', 'slug' => 'ip-cek']);
    $logoPath = UploadedFile::fake()->image('logo-baru.png')->store('logo', 'public');

    SiteSetting::current()->update([
        'company_name' => 'Studio Cek Logo',
        'logo_path' => $logoPath,
    ]);

    $logoUrl = SiteSetting::current()->logo_url;
    expect($logoUrl)->not->toBeNull();

    $this->get('/')
        ->assertSuccessful()
        ->assertSee('rel="icon" href="'.$logoUrl.'?v=', escape: false)
        ->assertDontSee('href="data:image/svg+xml', escape: false);

    // Public Original IP detail page: favicon + brand logo.
    $this->get(route('original-ip.show', 'ip-cek'))->assertSuccessful()->assertSee($logoUrl, escape: false);

    // Admin panel: sidebar brand + favicon, and every content page shares the same layout.
    $this->actingAs($admin)->get(route('admin.identitas'))->assertSuccessful()->assertSee($logoUrl, escape: false);
    $this->actingAs($admin)->get(route('admin.klien.index'))->assertSuccessful()->assertSee($logoUrl, escape: false);
});

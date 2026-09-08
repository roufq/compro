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

    $this->actingAs($admin)->put(route('admin.pengaturan.update'), [
        'company_name' => 'Studio Cek Logo',
        'logo' => UploadedFile::fake()->image('logo-baru.png'),
    ])->assertSessionHasNoErrors();

    $logoUrl = SiteSetting::current()->logo_url;
    expect($logoUrl)->not->toBeNull();

    // Public homepage: favicon link + navbar + footer logo.
    $this->get('/')->assertSuccessful()->assertSee($logoUrl, escape: false);

    // Public Original IP detail page: favicon + brand logo.
    $this->get(route('original-ip.show', 'ip-cek'))->assertSuccessful()->assertSee($logoUrl, escape: false);

    // Admin panel: sidebar brand + favicon, and every content page shares the same layout.
    $this->actingAs($admin)->get(route('admin.identitas'))->assertSuccessful()->assertSee($logoUrl, escape: false);
    $this->actingAs($admin)->get(route('admin.klien.index'))->assertSuccessful()->assertSee($logoUrl, escape: false);
});

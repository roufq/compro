<?php

use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

test('login panel shows default badge text and gradient tiles when nothing is set', function () {
    $this->get(route('login'))->assertSuccessful()
        ->assertSeeText('Studio Kreatif Bertenaga AI')
        ->assertSee('bg-linear-to-br from-[#6c5ce7]', escape: false);
});

test('admin can customize the login panel badge text and tile images', function () {
    Storage::fake('public');
    $admin = User::factory()->create();

    $this->actingAs($admin)
        ->put(route('admin.pengaturan.update'), [
            'company_name' => 'Studio',
            'hero_badge_text' => 'Teks Badge Kustom',
            'hero_tile_1' => UploadedFile::fake()->image('tile1.jpg'),
            'hero_tile_2' => UploadedFile::fake()->image('tile2.jpg'),
            'hero_tile_3' => UploadedFile::fake()->image('tile3.jpg'),
        ])->assertSessionHasNoErrors();

    $settings = SiteSetting::current();
    Storage::disk('public')->assertExists($settings->hero_tile_1_path);
    Storage::disk('public')->assertExists($settings->hero_tile_2_path);
    Storage::disk('public')->assertExists($settings->hero_tile_3_path);

    $this->post(route('logout'));
    $this->get(route('login'))->assertSuccessful()
        ->assertSeeText('Teks Badge Kustom')
        ->assertSee($settings->hero_tile_1_url)
        ->assertSee($settings->hero_tile_2_url)
        ->assertSee($settings->hero_tile_3_url);

    $this->actingAs($admin)->get(route('admin.hero'))->assertSuccessful()
        ->assertSee('value="Teks Badge Kustom"', escape: false)
        ->assertSee($settings->hero_tile_1_url);
});

test('a login panel tile image can be removed to fall back to the gradient', function () {
    Storage::fake('public');
    $admin = User::factory()->create();
    SiteSetting::current()->update(['hero_tile_1_path' => UploadedFile::fake()->image('tile1.jpg')->store('hero-tiles', 'public')]);

    $this->actingAs($admin)
        ->put(route('admin.pengaturan.update'), [
            'company_name' => 'Studio',
            'remove_hero_tile_1' => 1,
        ])->assertSessionHasNoErrors();

    expect(SiteSetting::current()->hero_tile_1_path)->toBeNull();
    $this->post(route('logout'));
    $this->get(route('login'))->assertSuccessful()
        ->assertSee('bg-linear-to-br from-[#6c5ce7]', escape: false);
});

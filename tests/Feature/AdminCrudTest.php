<?php

use App\Models\Client;
use App\Models\OriginalIp;
use App\Models\Portfolio;
use App\Models\Product;
use App\Models\Service;
use App\Models\TeamMember;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

test('team studio update persists text and the selected photo', function () {
    Storage::fake('public');
    $member = TeamMember::create(['name' => 'Nama Lama', 'role' => 'Peran Lama']);
    $photo = UploadedFile::fake()->image('anggota-baru.webp')->size(120);

    $this->actingAs(User::factory()->create())
        ->put(route('admin.tim.update', $member), [
            'name' => 'Nama Baru',
            'role' => 'Peran Baru',
            'photo' => $photo,
        ])
        ->assertSessionHasNoErrors()
        ->assertSessionHas('status', 'Anggota tim berhasil diperbarui.')
        ->assertRedirect();

    $member->refresh();

    expect($member->name)->toBe('Nama Baru')
        ->and($member->role)->toBe('Peran Baru')
        ->and($member->image_path)->not->toBeNull();
    Storage::disk('public')->assertExists($member->image_path);
});

test('all media CRUD updates persist their text and uploaded file', function (string $routeName, object $model, array $payload, string $fileField, string $pathAttribute) {
    Storage::fake('public');
    $payload[$fileField] = UploadedFile::fake()->image($fileField.'.webp')->size(50);

    $this->actingAs(User::factory()->create())
        ->put(route($routeName, $model), $payload)
        ->assertSessionHasNoErrors()
        ->assertRedirect();

    $model->refresh();

    expect($model->{$pathAttribute})->not->toBeNull();
    Storage::disk('public')->assertExists($model->{$pathAttribute});
})->with([
    'client' => fn () => [
        'admin.klien.update',
        Client::create(['name' => 'Klien Lama']),
        ['name' => 'Klien Baru'],
        'logo',
        'image_path',
    ],
    'original IP' => fn () => [
        'admin.original-ip.update',
        OriginalIp::create(['name' => 'IP Lama', 'slug' => 'ip-lama']),
        ['name' => 'IP Baru'],
        'cover',
        'image_path',
    ],
    'portfolio' => fn () => [
        'admin.portofolio.update',
        Portfolio::create([
            'title' => 'Karya Lama',
            'category' => 'branding',
            'type' => 'video',
            'youtube_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
        ]),
        [
            'title' => 'Karya Baru',
            'category' => 'video',
            'youtube_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
        ],
        'image',
        'image_path',
    ],
    'testimonial' => fn () => [
        'admin.testimoni.update',
        Testimonial::create(['name' => 'Pelanggan Lama', 'quote' => 'Lama']),
        ['name' => 'Pelanggan Baru', 'quote' => 'Baru'],
        'avatar',
        'avatar_path',
    ],
]);

test('all text CRUD updates persist changed data', function (string $routeName, object $model, array $payload, string $attribute, string $expected) {
    $this->actingAs(User::factory()->create())
        ->put(route($routeName, $model), $payload)
        ->assertSessionHasNoErrors()
        ->assertRedirect();

    expect($model->refresh()->{$attribute})->toBe($expected);
})->with([
    'service' => fn () => [
        'admin.layanan.update',
        Service::create(['title' => 'Layanan Lama', 'description' => 'Deskripsi lama']),
        ['title' => 'Layanan Baru', 'description' => 'Deskripsi baru'],
        'title',
        'Layanan Baru',
    ],
    'product' => fn () => [
        'admin.produk.update',
        Product::create(['name' => 'Produk Lama', 'url' => 'https://example.com/lama']),
        ['name' => 'Produk Baru', 'url' => 'https://example.com/baru'],
        'name',
        'Produk Baru',
    ],
]);

test('all content CRUD endpoints create and delete records', function (string $storeRoute, string $destroyRoute, string $modelClass, array $payload) {
    $admin = User::factory()->create();

    $this->actingAs($admin)
        ->post(route($storeRoute), $payload)
        ->assertSessionHasNoErrors()
        ->assertRedirect();

    $record = $modelClass::query()->latest('id')->first();
    expect($record)->not->toBeNull();

    $this->actingAs($admin)
        ->delete(route($destroyRoute, $record))
        ->assertSessionHasNoErrors()
        ->assertRedirect();

    expect($modelClass::query()->find($record->id))->toBeNull();
})->with([
    'client' => ['admin.klien.store', 'admin.klien.destroy', Client::class, ['name' => 'Klien Baru']],
    'team member' => ['admin.tim.store', 'admin.tim.destroy', TeamMember::class, ['name' => 'Anggota Baru', 'role' => 'Desainer']],
    'original IP' => ['admin.original-ip.store', 'admin.original-ip.destroy', OriginalIp::class, ['name' => 'IP Baru']],
    'portfolio' => ['admin.portofolio.store', 'admin.portofolio.destroy', Portfolio::class, [
        'title' => 'Karya Baru',
        'category' => 'branding',
        'youtube_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
    ]],
    'product' => ['admin.produk.store', 'admin.produk.destroy', Product::class, [
        'name' => 'Produk Baru',
        'url' => 'https://example.com/produk',
    ]],
    'service' => ['admin.layanan.store', 'admin.layanan.destroy', Service::class, [
        'title' => 'Layanan Baru',
        'description' => 'Deskripsi layanan.',
    ]],
    'testimonial' => ['admin.testimoni.store', 'admin.testimoni.destroy', Testimonial::class, [
        'name' => 'Pelanggan Baru',
        'quote' => 'Pelayanan sangat baik.',
    ]],
]);

test('renaming an original IP preserves its public slug', function () {
    $originalIp = OriginalIp::create(['name' => 'Nama Lama', 'slug' => 'nama-lama']);

    $this->actingAs(User::factory()->create())
        ->put(route('admin.original-ip.update', $originalIp), ['name' => 'Nama Baru'])
        ->assertSessionHasNoErrors();

    expect($originalIp->refresh()->name)->toBe('Nama Baru')
        ->and($originalIp->slug)->toBe('nama-lama');
});

test('file size feedback is rendered inside an open CRUD modal', function () {
    TeamMember::create(['name' => 'Anggota', 'role' => 'Desainer']);

    $this->actingAs(User::factory()->create())
        ->get(route('admin.tim.index'))
        ->assertSuccessful()
        ->assertSee("target: input.closest('dialog') || document.body", escape: false)
        ->assertSee("input.dataset.fileRejected = 'true'", escape: false)
        ->assertSee("title: 'Pilih file yang sesuai'", escape: false)
        ->assertSeeText('(opsional, maks. 150 KB)');
});

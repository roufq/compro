<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

test('admin can view the account settings page', function () {
    $admin = User::factory()->create(['name' => 'Nama Lama']);

    $this->actingAs($admin)
        ->get(route('admin.akun.edit'))
        ->assertSuccessful()
        ->assertSeeText('Informasi Akun')
        ->assertSeeText('Ubah Password')
        ->assertSee('value="Nama Lama"', escape: false);
});

test('admin can update their name', function () {
    $admin = User::factory()->create(['name' => 'Nama Lama']);

    $this->actingAs($admin)
        ->put(route('admin.akun.profil.update'), ['name' => 'Nama Baru'])
        ->assertSessionHasNoErrors()
        ->assertRedirect();

    expect($admin->fresh()->name)->toBe('Nama Baru');
});

test('updating the name requires a value', function () {
    $admin = User::factory()->create();

    $this->actingAs($admin)
        ->put(route('admin.akun.profil.update'), ['name' => ''])
        ->assertSessionHasErrors('name');
});

test('admin can change their password with the correct current password', function () {
    $admin = User::factory()->create(['password' => Hash::make('password-lama')]);

    $this->actingAs($admin)
        ->put(route('admin.akun.password.update'), [
            'current_password' => 'password-lama',
            'password' => 'password-baru-aman',
            'password_confirmation' => 'password-baru-aman',
        ])
        ->assertSessionHasNoErrors()
        ->assertRedirect();

    expect(Hash::check('password-baru-aman', $admin->fresh()->password))->toBeTrue();
});

test('changing password fails with the wrong current password', function () {
    $admin = User::factory()->create(['password' => Hash::make('password-lama')]);

    $this->actingAs($admin)
        ->put(route('admin.akun.password.update'), [
            'current_password' => 'password-salah',
            'password' => 'password-baru-aman',
            'password_confirmation' => 'password-baru-aman',
        ])
        ->assertSessionHasErrors('current_password');

    expect(Hash::check('password-lama', $admin->fresh()->password))->toBeTrue();
});

test('changing password requires confirmation to match', function () {
    $admin = User::factory()->create(['password' => Hash::make('password-lama')]);

    $this->actingAs($admin)
        ->put(route('admin.akun.password.update'), [
            'current_password' => 'password-lama',
            'password' => 'password-baru-aman',
            'password_confirmation' => 'tidak-cocok',
        ])
        ->assertSessionHasErrors('password');
});

test('the account page requires authentication', function () {
    $this->get(route('admin.akun.edit'))->assertRedirect(route('login'));
});

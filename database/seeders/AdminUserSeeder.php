<?php

namespace Database\Seeders;

use App\Actions\Teams\CreateTeam;
use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'adminweb@kedubes.id'],
            [
                'name' => 'Admin Kedubes Studio',
                'email_verified_at' => now(),
                'password' => 'Kedubes#24434',
            ],
        );

        if (! $admin->personalTeam()) {
            app(CreateTeam::class)->handle(
                user: $admin,
                name: 'Kedubes Studio',
                isPersonal: true,
            );
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class HomepageContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = SiteSetting::current();
        $defaults = [
            'about_title' => 'Beyond The Imagination',
            'about_description' => 'Kedubes Studio adalah studio produksi AI profesional yang berfokus pada commercial, animasi, dan pengembangan original IP. Kami memadukan teknologi generatif dengan arahan kreatif manusia di setiap tahap produksi — dari konsep, desain karakter, hingga hasil akhir siap tayang. Setiap karya dikerjakan dalam satu pipeline yang cepat namun tetap terkurasi, sehingga hasilnya konsisten secara visual dan matang secara cerita.',
            'clients' => array_map(fn (string $name): array => ['name' => $name], [
                'Pelita Kids', 'AiMaster.id', 'Jogja Coffee Week', 'Trans7', 'Trans TV', 'Garis10',
                'Project 1453', 'JOB Tomori Pertamina', 'Navara Gold', 'Svarga Tour', 'UMY', 'CESGS',
            ]),
            'original_ips' => [
                ['name' => 'Teman Ceria', 'description' => 'Original IP'],
                ['name' => 'Happy Friends', 'description' => 'Original IP'],
                ['name' => 'JAS — Jojo Acong Sitorus', 'description' => 'Original IP'],
            ],
            'team_members' => [],
            'products' => [],
        ];

        foreach ($defaults as $field => $value) {
            if ($settings->{$field} === null) {
                $settings->{$field} = $value;
            }
        }

        $settings->save();
    }
}

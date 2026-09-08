<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\OriginalIp;
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

        if ($settings->about_title === null) {
            $settings->about_title = 'Beyond The Imagination';
        }

        if ($settings->about_description === null) {
            $settings->about_description = 'Kedubes Studio adalah studio produksi AI profesional yang berfokus pada commercial, animasi, dan pengembangan original IP. Kami memadukan teknologi generatif dengan arahan kreatif manusia di setiap tahap produksi — dari konsep, desain karakter, hingga hasil akhir siap tayang. Setiap karya dikerjakan dalam satu pipeline yang cepat namun tetap terkurasi, sehingga hasilnya konsisten secara visual dan matang secara cerita.';
        }

        $settings->save();

        if (Client::query()->doesntExist()) {
            foreach ([
                'Pelita Kids', 'AiMaster.id', 'Jogja Coffee Week', 'Trans7', 'Trans TV', 'Garis10',
                'Project 1453', 'JOB Tomori Pertamina', 'Navara Gold', 'Svarga Tour', 'UMY', 'CESGS',
            ] as $index => $name) {
                Client::create(['name' => $name, 'order' => $index]);
            }
        }

        if (OriginalIp::query()->doesntExist()) {
            foreach ([
                'Teman Ceria', 'Happy Friends', 'JAS — Jojo Acong Sitorus',
            ] as $index => $name) {
                OriginalIp::create([
                    'name' => $name,
                    'slug' => OriginalIp::uniqueSlugFor($name),
                    'description' => 'Original IP',
                    'order' => $index,
                ]);
            }
        }
    }
}

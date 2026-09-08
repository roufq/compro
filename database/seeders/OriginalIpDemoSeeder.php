<?php

namespace Database\Seeders;

use App\Models\OriginalIp;
use Illuminate\Database\Seeder;

class OriginalIpDemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $examples = [
            'Teman Ceria' => [
                'description' => 'Bernyanyi, menari, dan belajar bersama sahabat lucu dalam bahasa Indonesia.',
                'details' => <<<'TEXT'
CONTOH KONTEN — untuk pratinjau tampilan. Nama karakter, cerita, dan ilustrasi berikut adalah konsep sementara.

Belajar jadi petualangan yang menyenangkan!
Teman Ceria mengajak balita mengenal dunia melalui lagu sederhana, tarian yang mudah diikuti, dan cerita kecil yang dekat dengan keseharian. Seluruh dialog serta lagu dirancang dalam bahasa Indonesia, dengan pengulangan kata agar anak dapat ikut bernyanyi bersama orang tua.

Kenalan dengan teman-teman kita
Milo, si kucing jingga, paling suka bernyanyi sambil memainkan marakas. Lili, kelinci putih yang ceria, mengajak semua teman bergerak dan menari. Dodo, dinosaurus hijau yang lembut, selalu penasaran dan senang mencoba hal baru.

Apa yang dipelajari?
Warna, angka 1–10, anggota tubuh, nama hewan, serta kebiasaan baik seperti mencuci tangan, berbagi mainan, dan mengucapkan terima kasih. Setiap episode mengangkat satu tema melalui musik, gerak, dan permainan sederhana.

Contoh episode
• Ayo Kenali Warna: Milo dan Lili mencampur warna untuk membuat lukisan taman.
• Satu, Dua, Tiga, Lompat!: Dodo menghitung langkah sambil menari bersama teman-temannya.
• Tangan Bersih, Hati Senang: lagu ceria sebelum menikmati bekal bersama.

Konsep format
Animasi musikal 3D • Bahasa Indonesia • Usia konsep 2–5 tahun • Durasi konsep 2–4 menit per lagu.

Referensi video pratinjau
Video di bawah adalah “Siapa Namamu” dari Marbel / Educa Studio, digunakan sebagai contoh tampilan pemutar dan referensi konten anak berbahasa Indonesia. Video tersebut bukan produksi Teman Ceria atau Kedubes Studio. Ganti dengan video asli melalui admin saat sudah tersedia.
TEXT,
                'image_url' => asset('images/original-ip-demo/teman-ceria.webp'),
                'gallery_urls' => implode("\n", [asset('images/original-ip-demo/teman-ceria.webp'), asset('images/original-ip-demo/teman-ceria-belajar-warna.webp')]),
                'video_urls' => 'https://www.youtube.com/watch?v=7OGI3tIrSpk',
            ],
            'Happy Friends' => [
                'description' => 'Sing, dance, and discover! An English-language musical adventure for little learners.',
                'details' => <<<'TEXT'
DEMO CONTENT — a preview concept. Character names, stories, and illustrations are temporary examples.

A little song, a big discovery
Happy Friends invites toddlers to explore everyday words through cheerful songs, playful movement, and simple stories. With English dialogue and songs, each adventure introduces a small set of words that families can repeat together.

Meet the friends
Ellie is a gentle blue elephant who loves to clap along. Sunny is a curious puppy who turns every little discovery into a game. Pip is a cheerful yellow duck who brings a happy rhythm wherever the friends go.

Learning through play
Colors, numbers, greetings, animals, and everyday routines become opportunities to sing and move. The friends practice taking turns, expressing feelings, and being kind through warm, familiar situations.

Sample episodes
• Hello, Happy Friends!: wave, clap, and say hello to a new friend.
• Five Little Apples: count colorful apples on a sunny picnic.
• Let's Clean Up!: turn tidying toys into a playful musical routine.

Format concept
3D musical animation • English • Concept audience: ages 2–5 • Concept length: 2–4 minutes per song.

Preview video reference
The video below is “Hello Hello! Can You Clap Your Hands?” by Super Simple Songs. It is an external reference to preview the video player and English-language musical format, not a Happy Friends or Kedubes Studio production. Replace it with your original video when available.
TEXT,
                'image_url' => asset('images/original-ip-demo/happy-friends.webp'),
                'gallery_urls' => implode("\n", [asset('images/original-ip-demo/happy-friends.webp'), asset('images/original-ip-demo/happy-friends-counting.webp')]),
                'video_urls' => 'https://www.youtube.com/watch?v=fN1Cyr0ZK9M',
            ],
            'JAS — Jojo Acong Sitorus' => [
                'description' => 'Tiga sahabat, sejuta cerita: petualangan kecil yang penuh tawa, kekompakan, dan kehangatan keluarga.',
                'details' => <<<'TEXT'
CONTOH KONTEN — untuk pratinjau tampilan. Cerita, sifat karakter, dan ilustrasi berikut adalah konsep sementara.

Persahabatan tumbuh dari hal-hal sederhana
JAS mengikuti keseharian Jojo, Acong, dan Sitorus di sebuah kampung Indonesia yang hangat. Mulai dari membuat layang-layang, membantu tetangga, sampai menyiapkan pentas sekolah, selalu ada kejadian lucu dan pelajaran kecil yang mereka temukan bersama.

Kenalan dengan JAS
Jojo penuh semangat dan sering menjadi pencetus ide, meskipun rencananya kadang terlalu terburu-buru. Acong teliti, kreatif, dan suka mencari cara memperbaiki barang. Sitorus humoris serta mudah berteman; ia sering membantu mencairkan suasana saat teman-temannya berbeda pendapat.

Dunia cerita
Halaman rumah, sekolah, warung, dan lapangan kampung menjadi tempat petualangan mereka. Cerita menonjolkan kerja sama, kejujuran, tanggung jawab, dan menghargai perbedaan melalui kejadian sehari-hari yang akrab bagi anak serta keluarga.

Contoh episode
• Layang-Layang untuk Semua: ketiga sahabat belajar berbagi tugas untuk menerbangkan layang-layang buatan sendiri.
• Misteri Bekal Tertukar: salah paham kecil berubah menjadi kesempatan saling mengenal.
• Panggung Kampung Kita: sebuah pertunjukan sederhana mempertemukan bakat seluruh teman.

Konsep format
Animasi komedi keluarga 3D • Bahasa Indonesia • Usia konsep 5–10 tahun dan keluarga • Durasi konsep 7–11 menit per episode.

Referensi video pratinjau
Video di bawah adalah episode Upin & Ipin dari Les’ Copaque Production, sebagai referensi format cerita keseharian anak yang Anda sebutkan. Video tersebut bukan produksi JAS atau Kedubes Studio. Karakter dan ilustrasi JAS di halaman ini merupakan konsep terpisah. Ganti referensi ini dengan episode asli saat sudah tersedia.
TEXT,
                'image_url' => asset('images/original-ip-demo/jas.webp'),
                'gallery_urls' => implode("\n", [asset('images/original-ip-demo/jas.webp'), asset('images/original-ip-demo/jas-bermain.webp')]),
                'video_urls' => 'https://www.youtube.com/watch?v=ZYMI8adms7c',
            ],
        ];

        foreach (OriginalIp::all() as $ip) {
            $fields = $examples[$ip->name] ?? null;

            if ($fields === null) {
                continue;
            }

            foreach ($fields as $field => $value) {
                if (blank($ip->{$field}) || ($field === 'description' && $ip->{$field} === 'Original IP')) {
                    $ip->{$field} = $value;
                }
            }

            $ip->save();
        }
    }
}

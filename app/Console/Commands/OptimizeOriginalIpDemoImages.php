<?php

namespace App\Console\Commands;

use App\Models\OriginalIp;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:optimize-original-ip-demo-images')]
#[Description('Convert demo IP images to WebP and update their saved URLs')]
class OptimizeOriginalIpDemoImages extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        if (! function_exists('imagewebp')) {
            $this->error('PHP GD with WebP support is required.');

            return self::FAILURE;
        }

        $replacements = [];
        $originalBytes = 0;
        $optimizedBytes = 0;

        foreach (['teman-ceria', 'teman-ceria-belajar-warna', 'happy-friends', 'happy-friends-counting', 'jas', 'jas-bermain'] as $name) {
            $relative = 'images/original-ip-demo/'.$name;
            $source = public_path($relative.'.png');
            $destination = public_path($relative.'.webp');

            if (! is_file($source)) {
                $this->error('Missing source image: '.$source);

                return self::FAILURE;
            }

            if (! is_file($destination)) {
                $image = imagecreatefrompng($source);
                if ($image === false) {
                    return self::FAILURE;
                }

                $resized = imagescale($image, min(1200, imagesx($image)));
                if ($resized === false || ! imagewebp($resized, $destination, 78)) {
                    $this->error('Could not optimize '.$source);

                    return self::FAILURE;
                }
            }

            $originalBytes += filesize($source);
            $optimizedBytes += filesize($destination);
            $replacements[asset($relative.'.png')] = asset($relative.'.webp');
            $replacements['/'.$relative.'.png'] = '/'.$relative.'.webp';
        }

        foreach (OriginalIp::all() as $ip) {
            $changed = false;

            if (isset($replacements[$ip->image_url ?? ''])) {
                $ip->image_url = $replacements[$ip->image_url];
                $changed = true;
            }

            if ($ip->gallery_urls) {
                $ip->gallery_urls = implode("\n", array_map(
                    fn (string $url): string => $replacements[trim($url)] ?? $url,
                    preg_split('/\R/', $ip->gallery_urls) ?: [],
                ));
                $changed = true;
            }

            if ($changed) {
                $ip->save();
            }
        }
        $this->info(sprintf('Demo images: %.2f MB -> %.2f MB (%.1f%% smaller).', $originalBytes / 1000000, $optimizedBytes / 1000000, (1 - $optimizedBytes / $originalBytes) * 100));

        return self::SUCCESS;
    }
}

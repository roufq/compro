<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('original_ips', function (Blueprint $table) {
            $table->string('image_path')->nullable()->after('image_url');
            $table->longText('gallery_paths')->nullable()->after('gallery_urls');
        });

        DB::table('original_ips')->orderBy('id')->eachById(function (object $originalIp): void {
            $coverPath = $this->storagePath($originalIp->image_url);
            [$galleryUrls, $galleryPaths] = $this->separateUrlsAndPaths($originalIp->gallery_urls);

            DB::table('original_ips')->where('id', $originalIp->id)->update([
                'image_path' => $coverPath,
                'image_url' => $coverPath ? null : $originalIp->image_url,
                'gallery_urls' => implode("\n", $galleryUrls),
                'gallery_paths' => implode("\n", $galleryPaths),
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('original_ips')->orderBy('id')->eachById(function (object $originalIp): void {
            $baseUrl = rtrim((string) config('app.url'), '/').'/storage/';
            $galleryPaths = preg_split('/\R/', $originalIp->gallery_paths ?? '', flags: PREG_SPLIT_NO_EMPTY) ?: [];
            $galleryUrls = preg_split('/\R/', $originalIp->gallery_urls ?? '', flags: PREG_SPLIT_NO_EMPTY) ?: [];

            DB::table('original_ips')->where('id', $originalIp->id)->update([
                'image_url' => $originalIp->image_url ?? ($originalIp->image_path ? $baseUrl.$originalIp->image_path : null),
                'gallery_urls' => implode("\n", array_merge(
                    $galleryUrls,
                    array_map(fn (string $path): string => $baseUrl.$path, $galleryPaths),
                )),
            ]);
        });

        Schema::table('original_ips', function (Blueprint $table) {
            $table->dropColumn(['image_path', 'gallery_paths']);
        });
    }

    private function storagePath(?string $url): ?string
    {
        if (! $url) {
            return null;
        }

        $path = parse_url($url, PHP_URL_PATH);

        return is_string($path) && Str::startsWith($path, '/storage/original-ips/')
            ? Str::after($path, '/storage/')
            : null;
    }

    /** @return array{0: array<int, string>, 1: array<int, string>} */
    private function separateUrlsAndPaths(?string $urls): array
    {
        $externalUrls = [];
        $paths = [];

        foreach (preg_split('/\R/', $urls ?? '', flags: PREG_SPLIT_NO_EMPTY) ?: [] as $url) {
            $path = $this->storagePath(trim($url));

            if ($path) {
                $paths[] = $path;
            } else {
                $externalUrls[] = trim($url);
            }
        }

        return [$externalUrls, $paths];
    }
};

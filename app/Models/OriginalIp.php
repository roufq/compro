<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

#[Fillable([
    'name',
    'slug',
    'description',
    'image_path',
    'image_url',
    'url',
    'details',
    'gallery_urls',
    'gallery_paths',
    'video_urls',
    'order',
])]
class OriginalIp extends Model
{
    protected $attributes = [
        'order' => 0,
    ];

    /**
     * Generate a slug for the given name that does not collide with any
     * existing original IP (optionally excluding one record, for updates).
     */
    public static function uniqueSlugFor(string $name, ?int $exceptId = null): string
    {
        $base = Str::slug(Str::limit($name, 150, '')) ?: 'original-ip';
        $slug = $base;
        $suffix = 2;

        while (
            static::query()
                ->where('slug', $slug)
                ->when($exceptId, fn ($query) => $query->where('id', '!=', $exceptId))
                ->exists()
        ) {
            $slug = $base.'-'.$suffix++;
        }

        return $slug;
    }

    /** @return array<int, string> */
    public function getPhotosAttribute(): array
    {
        $urls = preg_split('/\R/', $this->gallery_urls ?? '', flags: PREG_SPLIT_NO_EMPTY) ?: [];
        $paths = preg_split('/\R/', $this->gallery_paths ?? '', flags: PREG_SPLIT_NO_EMPTY) ?: [];

        return array_merge(
            $urls,
            array_map(fn (string $path): string => asset('storage/'.$path), $paths),
        );
    }

    public function getCoverUrlAttribute(): ?string
    {
        return $this->image_path
            ? asset('storage/'.$this->image_path)
            : $this->image_url;
    }

    /** @return Collection<int, non-falsy-string> */
    public function getVideoIdsAttribute(): Collection
    {
        return collect(preg_split('/\R/', $this->video_urls ?? '', flags: PREG_SPLIT_NO_EMPTY) ?: [])
            ->map(fn (string $url): ?string => Portfolio::youtubeIdFromUrl(trim($url)))
            ->filter()
            ->values();
    }
}

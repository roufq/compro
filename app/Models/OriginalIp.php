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
    'image_url',
    'url',
    'details',
    'gallery_urls',
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
        return preg_split('/\R/', $this->gallery_urls ?? '', flags: PREG_SPLIT_NO_EMPTY) ?: [];
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

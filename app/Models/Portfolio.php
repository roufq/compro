<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'title',
    'description',
    'category',
    'type',
    'image_path',
    'youtube_url',
    'order',
])]
class Portfolio extends Model
{
    protected $attributes = [
        'category' => 'branding',
        'type' => 'gambar',
        'order' => 0,
    ];

    public function isVideo(): bool
    {
        return $this->type === 'video';
    }

    public function getYoutubeIdAttribute(): ?string
    {
        return self::youtubeIdFromUrl($this->youtube_url);
    }

    public static function youtubeIdFromUrl(?string $url): ?string
    {
        if (! $url) {
            return null;
        }

        $parts = parse_url(trim($url));

        if (! is_array($parts) || ! isset($parts['host'])) {
            return null;
        }

        $host = strtolower($parts['host']);
        $pathSegments = array_values(array_filter(explode('/', trim($parts['path'] ?? '', '/'))));
        $videoId = null;

        if ($host === 'youtu.be' || str_ends_with($host, '.youtu.be')) {
            $videoId = $pathSegments[0] ?? null;
        } elseif ($host === 'youtube.com' || str_ends_with($host, '.youtube.com') || $host === 'youtube-nocookie.com' || str_ends_with($host, '.youtube-nocookie.com')) {
            parse_str($parts['query'] ?? '', $query);
            $videoId = $query['v'] ?? null;

            if (in_array($pathSegments[0] ?? null, ['embed', 'live', 'shorts', 'v'], true)) {
                $videoId = $pathSegments[1] ?? null;
            }
        }

        return is_string($videoId) && preg_match('/^[a-zA-Z0-9_-]{11}$/', $videoId)
            ? $videoId
            : null;
    }

    public function getYoutubeThumbnailAttribute(): ?string
    {
        return $this->youtube_id
            ? "https://img.youtube.com/vi/{$this->youtube_id}/hqdefault.jpg"
            : null;
    }

    public function getYoutubeEmbedAttribute(): ?string
    {
        return $this->youtube_id
            ? "https://www.youtube.com/embed/{$this->youtube_id}"
            : null;
    }

    public function getThumbnailUrlAttribute(): ?string
    {
        return $this->isVideo()
            ? $this->youtube_thumbnail
            : ($this->image_path ? asset('storage/'.$this->image_path) : null);
    }
}

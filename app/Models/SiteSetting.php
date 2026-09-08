<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'logo_path',
    'company_name',
    'tagline',
    'hero_title',
    'hero_description',
    'hero_badge_text',
    'hero_tile_1_path',
    'hero_tile_2_path',
    'hero_tile_3_path',
    'email',
    'phone',
    'address',
    'instagram_url',
    'linkedin_url',
    'youtube_url',
    'hero_image_path',
    'hero_video_url',
    'about_title',
    'about_description',
    'map_query',
])]
class SiteSetting extends Model
{
    public function getHeroImageUrlAttribute(): ?string
    {
        return $this->hero_image_path ? asset('storage/'.$this->hero_image_path) : null;
    }

    public function getHeroYoutubeIdAttribute(): ?string
    {
        return Portfolio::youtubeIdFromUrl($this->hero_video_url);
    }

    /**
     * Muted, looping, chromeless embed URL suitable for an autoplaying background video.
     */
    public function getHeroVideoEmbedAttribute(): ?string
    {
        $id = $this->hero_youtube_id;

        return $id
            ? "https://www.youtube-nocookie.com/embed/{$id}?autoplay=1&mute=1&loop=1&playlist={$id}&controls=0&modestbranding=1&rel=0&playsinline=1"
            : null;
    }

    public static function current(): self
    {
        // `id` is not mass-assignable, so firstOrCreate(['id' => 1], ...) can never
        // actually persist row 1 — it would silently create a new row every time
        // one goes missing. Look up the single settings row by order instead, and
        // only create one the very first time the table is empty.
        return static::query()->oldest('id')->first() ?? static::create([
            'company_name' => 'Kedubes Studio',
            'tagline' => 'Kedutaan Kreativitas AI',
            'hero_title' => 'Kreativitas tanpa batas, diperkuat kecerdasan buatan',
            'hero_description' => 'Kami memadukan strategi kreatif, teknologi AI, dan sentuhan manusia untuk menghasilkan karya visual yang berkesan.',
        ]);
    }

    public function getLogoUrlAttribute(): ?string
    {
        return $this->logo_path ? asset('storage/'.$this->logo_path) : null;
    }

    public function getHeroTile1UrlAttribute(): ?string
    {
        return $this->hero_tile_1_path ? asset('storage/'.$this->hero_tile_1_path) : null;
    }

    public function getHeroTile2UrlAttribute(): ?string
    {
        return $this->hero_tile_2_path ? asset('storage/'.$this->hero_tile_2_path) : null;
    }

    public function getHeroTile3UrlAttribute(): ?string
    {
        return $this->hero_tile_3_path ? asset('storage/'.$this->hero_tile_3_path) : null;
    }
}

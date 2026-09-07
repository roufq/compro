<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

#[Fillable([
    'logo_path',
    'company_name',
    'tagline',
    'hero_title',
    'hero_description',
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
    'clients',
    'original_ips',
    'team_members',
    'products',
])]
class SiteSetting extends Model
{
    /**
     * @param  array<int, array{name: string, slug?: string|null}>  $items
     * @return array<int, array{name: string, slug: string}>
     */
    public static function originalIpsWithSlugs(array $items): array
    {
        $usedSlugs = array_filter(array_column($items, 'slug'));

        foreach ($items as &$item) {
            if (! empty($item['slug'])) {
                continue;
            }

            $base = Str::slug(Str::limit($item['name'], 150, '')) ?: 'original-ip';
            $slug = $base;
            $suffix = 2;

            while (in_array($slug, $usedSlugs, true)) {
                $slug = $base.'-'.$suffix++;
            }

            $item['slug'] = $slug;
            $usedSlugs[] = $slug;
        }

        return array_values($items);
    }

    /** @return array<int, array{name: string, slug: string}> */
    public function originalIpItems(): array
    {
        return self::originalIpsWithSlugs($this->original_ips ?? []);
    }

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'clients' => 'array',
            'original_ips' => 'array',
            'team_members' => 'array',
            'products' => 'array',
        ];
    }

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
}

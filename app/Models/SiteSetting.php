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
    'email',
    'phone',
    'address',
    'instagram_url',
    'linkedin_url',
    'youtube_url',
])]
class SiteSetting extends Model
{
    public static function current(): self
    {
        return static::firstOrCreate(
            ['id' => 1],
            [
                'company_name' => 'Kedubes Studio',
                'tagline' => 'Kedutaan Kreativitas AI',
                'hero_title' => 'Kreativitas tanpa batas, diperkuat kecerdasan buatan',
                'hero_description' => 'Kami memadukan strategi kreatif, teknologi AI, dan sentuhan manusia untuk menghasilkan karya visual yang berkesan.',
            ],
        );
    }

    public function getLogoUrlAttribute(): ?string
    {
        return $this->logo_path ? asset('storage/'.$this->logo_path) : null;
    }
}

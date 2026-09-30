<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name', 'image_path', 'image_url', 'order'])]
class Client extends Model
{
    protected $attributes = [
        'order' => 0,
    ];

    public function getLogoUrlAttribute(): ?string
    {
        return $this->image_path
            ? asset('storage/'.$this->image_path)
            : $this->image_url;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name', 'role', 'quote', 'avatar_path', 'rating'])]
class Testimonial extends Model
{
    protected $attributes = [
        'rating' => 5,
    ];

    public function getAvatarUrlAttribute(): ?string
    {
        return $this->avatar_path ? asset('storage/'.$this->avatar_path) : null;
    }
}

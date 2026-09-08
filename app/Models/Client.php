<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name', 'image_url', 'order'])]
class Client extends Model
{
    protected $attributes = [
        'order' => 0,
    ];
}

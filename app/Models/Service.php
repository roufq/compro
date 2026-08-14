<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['title', 'description', 'icon', 'order'])]
class Service extends Model
{
    protected $attributes = [
        'order' => 0,
    ];
}

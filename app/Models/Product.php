<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name', 'description', 'url', 'order'])]
class Product extends Model
{
    protected $attributes = [
        'order' => 0,
    ];
}

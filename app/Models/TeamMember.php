<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name', 'role', 'image_url', 'order'])]
class TeamMember extends Model
{
    // Deliberately not `team_members` — that table already belongs to the
    // Teams/Membership pivot (see App\Models\Membership). This model backs
    // the public "Tim Studio" profile cards, an unrelated concept.
    protected $table = 'studio_team_members';

    protected $attributes = [
        'order' => 0,
    ];
}

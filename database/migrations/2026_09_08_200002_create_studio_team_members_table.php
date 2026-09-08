<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Note: this table is intentionally NOT named `team_members` — that table
     * name is already used by the Teams/Membership pivot (app/Models/Membership.php)
     * for user<->team access control. This table holds public "Tim Studio"
     * profile cards shown on the homepage, an unrelated concept.
     */
    public function up(): void
    {
        Schema::create('studio_team_members', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('role');
            $table->string('image_url', 2048)->nullable();
            $table->unsignedInteger('order')->default(0)->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('studio_team_members');
    }
};

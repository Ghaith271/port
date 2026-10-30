<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $fillable = ['name', 'title', 'tagline', 'bio', 'email', 'location', 'github_url', 'linkedin_url', 'instagram_url', 'phone', 'available'];

    protected function casts(): array
    {
        return ['available' => 'boolean'];
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = ['title', 'slug', 'category', 'description', 'tech_stack', 'project_url', 'github_url', 'featured', 'published', 'sort_order', 'accent_color'];

    protected function casts(): array
    {
        return ['tech_stack' => 'array', 'featured' => 'boolean', 'published' => 'boolean', 'sort_order' => 'integer'];
    }
}

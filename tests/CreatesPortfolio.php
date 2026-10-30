<?php

namespace Tests;

use App\Models\Profile;
use App\Models\Project;

trait CreatesPortfolio
{
    protected function createProfile(array $attributes = []): Profile
    {
        return Profile::create(array_merge([
            'name' => 'غيث',
            'title' => 'مطوّر ويب',
            'tagline' => 'أحوّل الأفكار إلى تجارب رقمية.',
            'bio' => 'أصمم وأطور تجارب ويب سريعة وممتعة.',
            'email' => 'hello@example.test',
            'location' => 'عن بُعد',
            'available' => true,
        ], $attributes));
    }

    protected function projectData(array $attributes = []): array
    {
        return array_merge([
            'title' => 'Customer portal',
            'slug' => 'customer-portal',
            'category' => 'تطبيقات ويب',
            'description' => 'A complete portal for customers and their projects.',
            'tech_stack' => 'Laravel, JavaScript، Bootstrap',
            'project_url' => 'https://example.test',
            'github_url' => 'https://github.com/example/portal',
            'featured' => '1',
            'published' => '1',
            'sort_order' => 2,
            'accent_color' => '#d7ff72',
        ], $attributes);
    }

    protected function createProject(array $attributes = []): Project
    {
        $data = $this->projectData($attributes);
        $data['tech_stack'] = ['Laravel', 'JavaScript', 'Bootstrap'];

        return Project::create($data);
    }
}

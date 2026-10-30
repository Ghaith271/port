<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function index(): View
    {
        return view('admin.projects.index', ['projects' => Project::orderBy('sort_order')->latest()->paginate(10)]);
    }

    public function create(): View
    {
        return view('admin.projects.create', ['project' => new Project(['published' => true, 'accent_color' => '#b8c6a0'])]);
    }

    public function store(Request $request): RedirectResponse
    {
        Project::create($this->data($request));

        return redirect()->route('admin.projects.index')->with('success', 'تمت إضافة المشروع.');
    }

    public function edit(Project $project): View
    {
        return view('admin.projects.edit', compact('project'));
    }

    public function update(Request $request, Project $project): RedirectResponse
    {
        $project->update($this->data($request, $project));

        return redirect()->route('admin.projects.index')->with('success', 'تم تحديث المشروع.');
    }

    public function destroy(Project $project): RedirectResponse
    {
        $project->delete();

        return back()->with('success', 'تم حذف المشروع.');
    }

    private function data(Request $request, ?Project $project = null): array
    {
        if (! $request->filled('slug')) {
            $request->merge(['slug' => $project?->slug ?? 'project-'.Str::lower(Str::random(8))]);
        }
        $data = $request->validate([
            'title' => ['required', 'string', 'max:150'],
            'slug' => ['required', 'string', 'max:180', 'regex:/^[a-zA-Z0-9_-]+$/', Rule::unique('projects')->ignore($project)],
            'category' => ['required', 'string', 'max:80'], 'description' => ['required', 'string', 'max:3000'],
            'tech_stack' => ['nullable', 'string', 'max:500'], 'project_url' => ['nullable', 'url:http,https', 'max:2048'],
            'github_url' => ['nullable', 'url:http,https', 'max:2048'], 'featured' => ['sometimes', 'boolean'],
            'published' => ['sometimes', 'boolean'], 'sort_order' => ['nullable', 'integer', 'between:0,9999'],
            'accent_color' => ['required', 'regex:/^#[0-9a-fA-F]{6}$/'],
        ]);
        $data['tech_stack'] = array_values(array_filter(array_map('trim', preg_split('/[,،]/u', $data['tech_stack'] ?? ''))));
        $data['featured'] = $request->boolean('featured');
        $data['published'] = $request->boolean('published');
        $data['sort_order'] = $data['sort_order'] ?? 0;

        return $data;
    }
}

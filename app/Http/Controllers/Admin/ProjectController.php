<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\UploadedFile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use RuntimeException;
use Throwable;

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
        $data = $this->data($request);
        $image = $data['image'] ?? null;
        unset($data['image'], $data['remove_image']);

        $newImagePath = null;
        try {
            if ($image instanceof UploadedFile) {
                $newImagePath = $this->storeImage($image);
                $data['image_path'] = $newImagePath;
            }

            DB::transaction(fn () => Project::create($data));
        } catch (Throwable $exception) {
            $this->deleteProjectImage($newImagePath);
            throw $exception;
        }

        return redirect()->route('admin.projects.index')->with('success', 'تمت إضافة المشروع.');
    }

    public function edit(Project $project): View
    {
        return view('admin.projects.edit', compact('project'));
    }

    public function update(Request $request, Project $project): RedirectResponse
    {
        $data = $this->data($request, $project);
        $image = $data['image'] ?? null;
        $removeImage = (bool) ($data['remove_image'] ?? false);
        unset($data['image'], $data['remove_image']);

        $oldImagePath = $project->image_path;
        $newImagePath = null;
        try {
            if ($image instanceof UploadedFile) {
                $newImagePath = $this->storeImage($image);
                $data['image_path'] = $newImagePath;
            } elseif ($removeImage) {
                $data['image_path'] = null;
            }

            DB::transaction(function () use ($project, $data): void {
                if (! $project->update($data)) {
                    throw new RuntimeException('Unable to update project.');
                }
            });
        } catch (Throwable $exception) {
            $this->deleteProjectImage($newImagePath);
            throw $exception;
        }

        if ($newImagePath !== null || $removeImage) {
            $this->deleteProjectImage($oldImagePath);
        }

        return redirect()->route('admin.projects.index')->with('success', 'تم تحديث المشروع.');
    }

    public function destroy(Project $project): RedirectResponse
    {
        $imagePath = $project->image_path;
        DB::transaction(function () use ($project): void {
            if (! $project->delete()) {
                throw new RuntimeException('Unable to delete project.');
            }
        });
        $this->deleteProjectImage($imagePath);

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
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'mimetypes:image/jpeg,image/png,image/webp', 'max:5120'],
            'remove_image' => ['sometimes', 'boolean'],
        ]);
        $data['tech_stack'] = array_values(array_filter(array_map('trim', preg_split('/[,،]/u', $data['tech_stack'] ?? ''))));
        $data['featured'] = $request->boolean('featured');
        $data['published'] = $request->boolean('published');
        $data['sort_order'] = $data['sort_order'] ?? 0;

        return $data;
    }

    private function storeImage(UploadedFile $image): string
    {
        $path = $image->store('projects', 'public');
        if (! is_string($path) || $path === '') {
            throw new RuntimeException('Unable to store project image.');
        }

        return $path;
    }

    private function deleteProjectImage(?string $path): void
    {
        if ($path === null || ! str_starts_with($path, 'projects/') || basename($path) !== substr($path, strlen('projects/'))) {
            return;
        }

        if (! Storage::disk('public')->delete($path)) {
            Log::warning('Unable to delete project image.', ['path' => $path]);
        }
    }
}

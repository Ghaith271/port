<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Skill;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SkillController extends Controller
{
    public function index(): View
    {
        return view('admin.skills.index', ['skills' => Skill::orderBy('sort_order')->get()]);
    }

    public function store(Request $request): RedirectResponse
    {
        Skill::create($this->data($request));

        return back()->with('success', 'تمت إضافة المهارة.');
    }

    public function update(Request $request, Skill $skill): RedirectResponse
    {
        $skill->update($this->data($request));

        return back()->with('success', 'تم تحديث المهارة.');
    }

    public function destroy(Skill $skill): RedirectResponse
    {
        $skill->delete();

        return back()->with('success', 'تم حذف المهارة.');
    }

    private function data(Request $request): array
    {
        $data = $request->validate(['name' => ['required', 'string', 'max:100'], 'category' => ['required', 'string', 'max:100'], 'level' => ['required', 'integer', 'between:0,100'], 'sort_order' => ['nullable', 'integer', 'between:0,9999']]);
        $data['sort_order'] = $data['sort_order'] ?? 0;

        return $data;
    }
}

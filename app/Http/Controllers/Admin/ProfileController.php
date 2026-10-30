<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(): View
    {
        return view('admin.profile.edit', ['profile' => Profile::firstOrFail()]);
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'], 'title' => ['required', 'string', 'max:150'],
            'tagline' => ['required', 'string', 'max:255'], 'bio' => ['required', 'string', 'max:5000'],
            'email' => ['nullable', 'email', 'max:254'], 'location' => ['nullable', 'string', 'max:150'],
            'github_url' => ['nullable', 'url:http,https', 'max:2048'],
            'linkedin_url' => ['nullable', 'url:http,https', 'max:2048'],
            'instagram_url' => ['nullable', 'url:http,https', 'max:2048'],
            'phone' => ['nullable', 'string', 'max:30', 'regex:/^\+?(?=(?:\D*\d){7,15}\D*$)[0-9 ()-]+$/'], 'available' => ['sometimes', 'boolean'],
        ]);
        $data['available'] = $request->boolean('available');
        Profile::firstOrFail()->update($data);

        return back()->with('success', 'تم حفظ بياناتك الشخصية.');
    }
}

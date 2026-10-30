<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Profile;
use App\Models\Project;
use App\Models\Skill;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PortfolioController extends Controller
{
    public function index(): View
    {
        return view('portfolio', [
            'profile' => Profile::firstOrFail(),
            'projects' => Project::where('published', true)->orderByDesc('featured')->orderBy('sort_order')->get(),
            'skills' => Skill::orderBy('sort_order')->get(),
        ]);
    }

    public function contact(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'], 'email' => ['required', 'email', 'max:254'],
            'subject' => ['required', 'string', 'max:200'], 'message' => ['required', 'string', 'min:10', 'max:5000'],
            'website' => ['nullable', 'string', 'max:0'],
        ]);
        unset($data['website']);
        Message::create($data);

        return redirect()->to(route('home').'#contact')->with('success', 'وصلت رسالتك بنجاح، شكرًا لتواصلك!');
    }
}

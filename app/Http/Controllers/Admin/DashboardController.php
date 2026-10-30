<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\Project;
use App\Models\Skill;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('admin.dashboard', [
            'projectCount' => Project::count(), 'skillCount' => Skill::count(), 'messageCount' => Message::count(),
            'unreadCount' => Message::whereNull('read_at')->count(),
            'recentMessages' => Message::latest()->limit(5)->get(),
            'recentProjects' => Project::orderByDesc('updated_at')->limit(4)->get(),
        ]);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Project;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('admin.dashboard', [
            'companyCount' => Company::count(),
            'publishedCompanyCount' => Company::where('published', true)->count(),
            'projectCount' => Project::count(),
            'featuredProjectCount' => Project::where('featured', true)->count(),
            'recentProjects' => Project::with('company')->latest()->take(5)->get(),
        ]);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Project;
use App\Models\Service;
use App\Models\TeamMember;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    use ApiResponse;

    public function stats(): JsonResponse
    {
        return $this->success([
            'projects' => Project::count(),
            'news' => Article::count(),
            'services' => Service::count(),
            'team_members' => TeamMember::count(),
            'published_projects' => Project::where('status', 'published')->count(),
            'published_articles' => Article::where('status', 'published')->count(),
            'active_services' => Service::where('status', 'active')->count(),
            'active_team_members' => TeamMember::where('status', 'active')->count(),
        ], 'Dashboard stats retrieved successfully');
    }
}

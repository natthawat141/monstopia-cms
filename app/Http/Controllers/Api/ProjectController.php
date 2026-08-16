<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectRequest;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;

class ProjectController extends Controller
{
    use ApiResponse;

    public function index(): JsonResponse
    {
        $projects = Project::query()
            ->with('category')
            ->when(request('search'), fn ($query, $search) => $query->where(function ($builder) use ($search) {
                $builder->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('client_name', 'like', "%{$search}%");
            }))
            ->when(request('status'), fn ($query, $status) => $query->where('status', $status))
            ->when(request('category_id'), fn ($query, $categoryId) => $query->where('category_id', $categoryId))
            ->latest()
            ->paginate(min(max((int) request('per_page', 10), 1), 100))
            ->withQueryString();

        return $this->collection(
            ProjectResource::collection($projects->items()),
            $projects,
            'Projects retrieved successfully',
        );
    }

    public function store(ProjectRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['published_at'] = ($data['status'] ?? null) === 'published'
            ? ($data['published_at'] ?? now())
            : null;

        return $this->success(
            new ProjectResource(Project::create($data)->load('category')),
            'Project created successfully',
            201,
        );
    }

    public function show(Project $project): JsonResponse
    {
        return $this->success(new ProjectResource($project->load('category')), 'Project retrieved successfully');
    }

    public function update(ProjectRequest $request, Project $project): JsonResponse
    {
        $data = $request->validated();
        $data['published_at'] = ($data['status'] ?? null) === 'published'
            ? ($data['published_at'] ?? $project->published_at ?? now())
            : null;
        $project->update($data);

        return $this->success(new ProjectResource($project->refresh()->load('category')), 'Project updated successfully');
    }

    public function destroy(Project $project): JsonResponse
    {
        $project->delete();
        return $this->success(null, 'Project deleted successfully');
    }
}

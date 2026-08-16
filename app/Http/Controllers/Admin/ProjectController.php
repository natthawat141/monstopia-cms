<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectRequest;
use App\Models\Company;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function index(): View
    {
        $projects = Project::with('company')
            ->when(request('search'), fn ($query, $search) => $query->where(function ($builder) use ($search) {
                $builder->where('name', 'like', "%{$search}%")
                    ->orWhere('summary', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%");
            }))
            ->when(request('status'), fn ($query, $status) => $query->where('status', $status))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.projects.index', compact('projects'));
    }

    public function create(): View
    {
        return view('admin.projects.create', [
            'project' => new Project(['status' => 'published', 'featured' => true]),
            'companies' => Company::orderBy('name')->get(),
        ]);
    }

    public function store(ProjectRequest $request): RedirectResponse
    {
        $project = Project::create($this->normalizeInput($request->validated()));

        return redirect()->route('admin.projects.show', $project)->with('success', 'สร้างข้อมูลโครงการเรียบร้อยแล้ว');
    }

    public function show(Project $project): View
    {
        return view('admin.projects.show', ['project' => $project->load('company')]);
    }

    public function edit(Project $project): View
    {
        return view('admin.projects.edit', [
            'project' => $project,
            'companies' => Company::orderBy('name')->get(),
        ]);
    }

    public function update(ProjectRequest $request, Project $project): RedirectResponse
    {
        $project->update($this->normalizeInput($request->validated()));

        return redirect()->route('admin.projects.show', $project)->with('success', 'อัปเดตข้อมูลโครงการเรียบร้อยแล้ว');
    }

    public function destroy(Project $project): RedirectResponse
    {
        $project->delete();

        return redirect()->route('admin.projects.index')->with('success', 'ลบข้อมูลโครงการเรียบร้อยแล้ว');
    }

    private function normalizeInput(array $data): array
    {
        foreach (['partners', 'tags'] as $field) {
            $data[$field] = collect($data[$field] ?? [])
                ->map(fn ($value) => trim((string) $value))
                ->filter()
                ->values()
                ->all();
        }

        $data['sources'] = collect($data['sources'] ?? [])
            ->filter(fn ($source) => filled($source['label'] ?? null) || filled($source['url'] ?? null))
            ->map(fn ($source) => [
                'label' => trim((string) ($source['label'] ?? '')),
                'url' => trim((string) ($source['url'] ?? '')),
            ])
            ->values()
            ->all();

        return $data;
    }
}

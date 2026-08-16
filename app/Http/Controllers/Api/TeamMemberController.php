<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TeamMemberRequest;
use App\Http\Resources\TeamMemberResource;
use App\Models\TeamMember;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;

class TeamMemberController extends Controller
{
    use ApiResponse;

    public function index(): JsonResponse
    {
        $members = TeamMember::query()
            ->when(request('search'), fn ($query, $search) => $query->where(function ($builder) use ($search) {
                $builder->where('name', 'like', "%{$search}%")
                    ->orWhere('position', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            }))
            ->when(request('status'), fn ($query, $status) => $query->where('status', $status))
            ->latest()
            ->paginate(min(max((int) request('per_page', 10), 1), 100))
            ->withQueryString();

        return $this->collection(TeamMemberResource::collection($members->items()), $members, 'Team members retrieved successfully');
    }

    public function store(TeamMemberRequest $request): JsonResponse
    {
        return $this->success(new TeamMemberResource(TeamMember::create($request->validated())), 'Team member created successfully', 201);
    }

    public function show(TeamMember $teamMember): JsonResponse
    {
        return $this->success(new TeamMemberResource($teamMember), 'Team member retrieved successfully');
    }

    public function update(TeamMemberRequest $request, TeamMember $teamMember): JsonResponse
    {
        $teamMember->update($request->validated());
        return $this->success(new TeamMemberResource($teamMember->refresh()), 'Team member updated successfully');
    }

    public function destroy(TeamMember $teamMember): JsonResponse
    {
        $teamMember->delete();
        return $this->success(null, 'Team member deleted successfully');
    }
}

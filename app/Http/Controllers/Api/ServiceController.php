<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ServiceRequest;
use App\Http\Resources\ServiceResource;
use App\Models\Service;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;

class ServiceController extends Controller
{
    use ApiResponse;

    public function index(): JsonResponse
    {
        $services = Service::query()
            ->when(request('search'), fn ($query, $search) => $query->where(function ($builder) use ($search) {
                $builder->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            }))
            ->when(request('status'), fn ($query, $status) => $query->where('status', $status))
            ->orderBy('sort_order')
            ->paginate(min(max((int) request('per_page', 10), 1), 100))
            ->withQueryString();

        return $this->collection(ServiceResource::collection($services->items()), $services, 'Services retrieved successfully');
    }

    public function store(ServiceRequest $request): JsonResponse
    {
        return $this->success(new ServiceResource(Service::create($request->validated())), 'Service created successfully', 201);
    }

    public function show(Service $service): JsonResponse
    {
        return $this->success(new ServiceResource($service), 'Service retrieved successfully');
    }

    public function update(ServiceRequest $request, Service $service): JsonResponse
    {
        $service->update($request->validated());
        return $this->success(new ServiceResource($service->refresh()), 'Service updated successfully');
    }

    public function destroy(Service $service): JsonResponse
    {
        $service->delete();
        return $this->success(null, 'Service deleted successfully');
    }
}

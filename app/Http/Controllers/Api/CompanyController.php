<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CompanyRequest;
use App\Http\Resources\CompanyResource;
use App\Models\Company;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;

class CompanyController extends Controller
{
    use ApiResponse;

    public function index(): JsonResponse
    {
        $companies = Company::query()
            ->when(request('search'), fn ($query, $search) => $query->where(function ($builder) use ($search) {
                $builder->where('name', 'like', "%{$search}%")
                    ->orWhere('legal_name', 'like', "%{$search}%")
                    ->orWhere('province', 'like', "%{$search}%");
            }))
            ->latest()
            ->paginate(min(max((int) request('per_page', 10), 1), 100));

        return $this->collection(CompanyResource::collection($companies->items()), $companies, 'Companies retrieved successfully');
    }

    public function store(CompanyRequest $request): JsonResponse
    {
        return $this->success(new CompanyResource(Company::create($request->validated())), 'Company created successfully', 201);
    }

    public function show(Company $company): JsonResponse
    {
        return $this->success(new CompanyResource($company), 'Company retrieved successfully');
    }

    public function update(CompanyRequest $request, Company $company): JsonResponse
    {
        $company->update($request->validated());
        return $this->success(new CompanyResource($company->refresh()), 'Company updated successfully');
    }

    public function destroy(Company $company): JsonResponse
    {
        $company->delete();
        return $this->success(null, 'Company deleted successfully');
    }
}

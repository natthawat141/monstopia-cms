<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    use ApiResponse;

    public function index(): JsonResponse
    {
        $categories = Category::query()
            ->withCount(['projects', 'articles'])
            ->when(request('search'), fn ($query, $search) => $query->where('name', 'like', "%{$search}%"))
            ->when(request('status'), fn ($query, $status) => $query->where('status', $status))
            ->orderBy('name')
            ->paginate(min(max((int) request('per_page', 50), 1), 100));

        return $this->collection(CategoryResource::collection($categories->items()), $categories, 'Categories retrieved successfully');
    }

    public function store(CategoryRequest $request): JsonResponse
    {
        return $this->success(new CategoryResource(Category::create($request->validated())), 'Category created successfully', 201);
    }

    public function show(Category $category): JsonResponse
    {
        return $this->success(new CategoryResource($category->loadCount(['projects', 'articles'])), 'Category retrieved successfully');
    }

    public function update(CategoryRequest $request, Category $category): JsonResponse
    {
        $category->update($request->validated());
        return $this->success(new CategoryResource($category->refresh()->loadCount(['projects', 'articles'])), 'Category updated successfully');
    }

    public function destroy(Category $category): JsonResponse
    {
        $category->delete();
        return $this->success(null, 'Category deleted successfully');
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ArticleRequest;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;

class ArticleController extends Controller
{
    use ApiResponse;

    public function index(): JsonResponse
    {
        $articles = Article::query()
            ->with('category')
            ->when(request('search'), fn ($query, $search) => $query->where(function ($builder) use ($search) {
                $builder->where('title', 'like', "%{$search}%")
                    ->orWhere('summary', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%");
            }))
            ->when(request('status'), fn ($query, $status) => $query->where('status', $status))
            ->when(request('category_id'), fn ($query, $categoryId) => $query->where('category_id', $categoryId))
            ->latest()
            ->paginate(min(max((int) request('per_page', 10), 1), 100))
            ->withQueryString();

        return $this->collection(ArticleResource::collection($articles->items()), $articles, 'Articles retrieved successfully');
    }

    public function store(ArticleRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['published_at'] = ($data['status'] ?? null) === 'published'
            ? ($data['published_at'] ?? now())
            : null;

        return $this->success(new ArticleResource(Article::create($data)->load('category')), 'Article created successfully', 201);
    }

    public function show(Article $article): JsonResponse
    {
        return $this->success(new ArticleResource($article->load('category')), 'Article retrieved successfully');
    }

    public function update(ArticleRequest $request, Article $article): JsonResponse
    {
        $data = $request->validated();
        $data['published_at'] = ($data['status'] ?? null) === 'published'
            ? ($data['published_at'] ?? $article->published_at ?? now())
            : null;
        $article->update($data);

        return $this->success(new ArticleResource($article->refresh()->load('category')), 'Article updated successfully');
    }

    public function destroy(Article $article): JsonResponse
    {
        $article->delete();
        return $this->success(null, 'Article deleted successfully');
    }
}

<?php

use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\TeamMemberController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

foreach ([
    'companies' => CompanyController::class,
    'categories' => CategoryController::class,
    'projects' => ProjectController::class,
    'articles' => ArticleController::class,
    'services' => ServiceController::class,
    'team-members' => TeamMemberController::class,
] as $uri => $controller) {
    Route::apiResource($uri, $controller)->only(['index', 'show']);
}

Route::middleware(['web', 'auth', 'role:admin,editor'])->group(function () {
    foreach ([
        'companies' => CompanyController::class,
        'categories' => CategoryController::class,
        'projects' => ProjectController::class,
        'articles' => ArticleController::class,
        'services' => ServiceController::class,
        'team-members' => TeamMemberController::class,
    ] as $uri => $controller) {
        Route::apiResource($uri, $controller)->only(['store', 'update', 'destroy']);
    }

    Route::get('/dashboard/stats', [DashboardController::class, 'stats']);
    Route::get('/me', fn (Request $request) => response()->json([
        'success' => true,
        'message' => 'Authenticated user retrieved successfully',
        'data' => $request->user(),
    ]));
});

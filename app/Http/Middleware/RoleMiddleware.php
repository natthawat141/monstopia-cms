<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (! $request->user() || ($roles !== [] && ! in_array($request->user()->role, $roles, true))) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return new JsonResponse([
                    'success' => false,
                    'message' => $request->user() ? 'Forbidden' : 'Unauthenticated',
                ], $request->user() ? 403 : 401);
            }

            return redirect()->route('login');
        }

        return $next($request);
    }
}

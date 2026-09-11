<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Server-side gate for every /admin/* route.
 * This is the ONLY thing that decides admin access — never the frontend,
 * never a hidden field, never the AI. Register in bootstrap/app.php as
 * ->withMiddleware(fn ($m) => $m->alias(['admin' => EnsureUserIsAdmin::class]))
 * and apply with Route::middleware(['auth', 'admin'])->group(...).
 */
class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user() || !$request->user()->isAdmin()) {
            abort(403, 'You do not have permission to access this area.');
        }

        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSuperAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user() || ! $request->user()->isSuperAdmin()) {
            return redirect()
                ->route('dashboard')
                ->with('error', 'Fitur manajemen user hanya dapat diakses oleh super admin.');
        }

        return $next($request);
    }
}

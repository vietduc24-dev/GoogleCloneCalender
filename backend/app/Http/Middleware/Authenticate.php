<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        // Không redirect nếu có bất kỳ điều kiện nào sau đây
        if ($request->is('api/*') || 
            $request->ajax() || 
            $request->wantsJson() || 
            $request->header('X-Requested-With') === 'XMLHttpRequest' ||
            $request->header('Accept') === 'application/json' ||
            $request->header('X-No-Redirect') === 'true' ||
            $request->header('X-Inertia') === 'true'
        ) {
            abort(401, 'Unauthenticated');
        }
        
        return route('login');
    }
} 
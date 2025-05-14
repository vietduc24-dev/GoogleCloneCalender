<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class HandleCors
{
    public function handle(Request $request, Closure $next)
    {
        $origin = $request->header('Origin');
        $allowedOrigins = ['http://localhost:8000', 'http://127.0.0.1:8000'];

        // If origin is not allowed, proceed without CORS headers
        if (!in_array($origin, $allowedOrigins)) {
            return $next($request);
        }

        if ($request->isMethod('OPTIONS')) {
            $response = response('', 200);
        } else {
            $response = $next($request);
        }

        // Set CORS headers
        $response->headers->set('Access-Control-Allow-Origin', $origin);
        $response->headers->set('Access-Control-Allow-Credentials', 'true');
        $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, PATCH, DELETE, OPTIONS');
        $response->headers->set('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, X-Token-Auth, Authorization, X-XSRF-TOKEN, Accept');
        $response->headers->set('Access-Control-Expose-Headers', 'X-XSRF-TOKEN');
        $response->headers->set('Access-Control-Max-Age', '86400');
        
        // Ensure Vary header is set
        $response->headers->set('Vary', 'Origin');

        return $response;
    }
} 
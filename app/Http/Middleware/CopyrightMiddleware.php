<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CopyrightMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Server-level headers protecting the authorship of Muslim Gunawan
        $response->headers->set('X-Copyright-Owner', 'Muslim Gunawan');
        $response->headers->set('X-Protected-By', 'Muslim Gunawan Security Engine');
        $response->headers->set('X-Software-Developer', 'Muslim Gunawan');

        return $response;
    }
}

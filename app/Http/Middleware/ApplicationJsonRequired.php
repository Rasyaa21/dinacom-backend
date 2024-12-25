<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApplicationJsonRequired
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->isJson()){
            return response()->json([
                'error' => 'accept header must be application/json'
            ], Response::HTTP_UNSUPPORTED_MEDIA_TYPE );
        }
        return $next($request);
    }
}

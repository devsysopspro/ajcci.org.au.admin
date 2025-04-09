<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;

class LogRoute
{
    public function handle($request, Closure $next)
    {
        Log::info('Request Method: ' . $request->method());
        Log::info('Request URL: ' . $request->fullUrl());
        Log::info('Request Headers: ', $request->headers->all());
        
        $response = $next($request);
        
        Log::info('Response Status: ' . $response->status());
        Log::info('Response Content: ' . $response->content());
        
        return $response;
    }
}

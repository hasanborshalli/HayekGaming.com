<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LogSlowRequests
{
    public function handle(Request $request, Closure $next)
    {
        $start = microtime(true);

        $response = $next($request);

        $timeMs = round((microtime(true) - $start) * 1000, 2);

        if ($timeMs > 1000) {
            Log::warning('SLOW REQUEST', [
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'time_ms' => $timeMs,
                'ip' => $request->ip(),
            ]);
        }

        return $response;
    }
}
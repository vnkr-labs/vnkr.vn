<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

/**
 * Ý tưởng từ: github/docs src/observability
 * - Gán UUID duy nhất cho mỗi request (dễ trace log)
 * - Structured logging theo format logfmt
 * - Tính response time
 */
class RequestObservability
{
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Gán request UUID (như requestUuid trong github/docs)
        $uuid = (string) Str::uuid();
        $request->attributes->set('request_uuid', $uuid);

        $start = microtime(true);

        // 2. Xử lý request
        $response = $next($request);

        // 3. Thêm UUID vào response header (dễ debug)
        $response->headers->set('X-Request-ID', $uuid);

        // 4. Structured logging (logfmt style như github/docs)
        $duration = round((microtime(true) - $start) * 1000, 2); // ms
        $status   = $response->getStatusCode();

        // Chỉ log những request đáng quan tâm (không log assets)
        $path = $request->path();
        if (!$this->shouldSkip($path)) {
            $logData = [
                'uuid'     => $uuid,
                'method'   => $request->method(),
                'path'     => '/' . $path,
                'status'   => $status,
                'duration' => "{$duration}ms",
                'ip'       => $request->ip(),
                'user_id'  => auth()->id() ?? 'guest',
            ];

            $level = $status >= 500 ? 'error' : ($status >= 400 ? 'warning' : 'info');
            Log::channel('daily')->$level('request', $logData);
        }

        return $response;
    }

    private function shouldSkip(string $path): bool
    {
        return str_starts_with($path, '_')           // Ignition, debugbar
            || str_starts_with($path, 'horizon')
            || str_ends_with($path, '.ico')
            || str_ends_with($path, '.css')
            || str_ends_with($path, '.js')
            || $path === 'health';
    }
}

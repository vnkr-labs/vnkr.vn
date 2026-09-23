<?php

namespace App\Http\Middleware;

use App\Models\Redirect;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Ý tưởng từ: github/docs src/redirects handle-redirects middleware
 * Xử lý redirect từ DB trước khi đến router.
 * Đăng ký trong Kernel.php → $middlewareGroups['web']
 */
class HandleDatabaseRedirects
{
    public function handle(Request $request, Closure $next): Response
    {
        $path = '/' . ltrim($request->path(), '/');

        $redirect = Redirect::resolve($path);

        if ($redirect) {
            return redirect($redirect['to'], $redirect['status']);
        }

        return $next($request);
    }
}

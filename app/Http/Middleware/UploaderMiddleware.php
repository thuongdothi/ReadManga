<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UploaderMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check() || !auth()->user()->isUploader()) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Bạn không có quyền đăng tải truyện.'], 403);
            }
            return redirect()->route('home')->with('error', 'Bạn không có quyền đăng tải truyện.');
        }

        return $next($request);
    }
}

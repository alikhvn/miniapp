<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfNotAuthenticated
{
    public function handle(Request $request, Closure $next, $guard = null)
    {
        if (!Auth::check()) {
            // Просто продолжаем, так как TelegramAuth автоматически аутентифицирует
            return $next($request);
        }

        return $next($request);
    }
}

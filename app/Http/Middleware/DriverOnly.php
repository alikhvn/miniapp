<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class DriverOnly
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('home')->with('error', 'Необходима авторизация');
        }

        if (Auth::user()->role !== 'driver') {
            return redirect()->route('home')
                ->with('error', 'Доступ только для водителей');
        }

        return $next($request);
    }
}

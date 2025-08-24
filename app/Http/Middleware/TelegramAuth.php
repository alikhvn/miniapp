<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class TelegramAuth
{
    public function handle(Request $request, Closure $next)
    {
        // Если пользователь уже аутентифицирован, пропускаем
        if (Auth::check()) {
            return $next($request);
        }

        // Создаем тестового пользователя для разработки
        $testUserData = [
            'id' => rand(100000000, 999999999),
            'first_name' => 'Тестовый',
            'last_name' => 'Пользователь',
            'username' => 'testuser' . rand(1000, 9999),
            'photo_url' => null
        ];

        // Создаем или находим пользователя
        $user = User::firstOrCreate(
            ['telegram_id' => $testUserData['id']],
            [
                'first_name' => $testUserData['first_name'],
                'last_name' => $testUserData['last_name'],
                'username' => $testUserData['username'],
                'photo_url' => $testUserData['photo_url'],
                'name' => $testUserData['first_name'] . ' ' . $testUserData['last_name'],
                'email' => $testUserData['username'] . '@telegram.miniapp',
                'password' => bcrypt('telegram_auth_' . time())
            ]
        );

        Auth::login($user);

        return $next($request);
    }
}

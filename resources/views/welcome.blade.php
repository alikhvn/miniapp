<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Mini App</title>
    @vite('resources/css/app.css')
    <script src="https://telegram.org/js/telegram-web-app.js"></script>
</head>
<body class="bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-white min-h-screen flex flex-col">

<header class="p-4 bg-blue-600 text-white text-lg font-bold text-center">
    Мой Telegram Mini App
</header>

<main class="flex-1 p-4">
    <h1 class="text-xl font-semibold">Привет, <span id="username">пользователь</span> 👋</h1>
    <p class="mt-2 text-sm">Это минимальное приложение на Blade + Tailwind.</p>

    <button onclick="tg.close()"
            class="mt-4 w-full bg-green-500 text-white py-2 rounded-xl">
        Закрыть приложение
    </button>
</main>
</body>
</html>

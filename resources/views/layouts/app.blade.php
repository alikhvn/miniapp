<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>TaxiApp - Такси для малых городов</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .active-tab {
            color: #3B82F6;
            position: relative;
        }
        .active-tab::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 50%;
            transform: translateX(-50%);
            width: 6px;
            height: 6px;
            background: #3B82F6;
            border-radius: 50%;
        }
        .btn-primary {
            background: linear-gradient(135deg, #3B82F6 0%, #2563EB 100%);
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }
        .card-hover {
            transition: all 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-900 flex flex-col min-h-screen">

<!-- Header -->
<header class="bg-white shadow-sm border-b">
    <div class="max-w-md mx-auto px-4 py-3">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-blue-500 rounded-xl flex items-center justify-center mr-3">
                    <span class="material-icons text-white">directions_car</span>
                </div>
                <h1 class="text-xl font-bold text-gray-800">TaxiApp</h1>
            </div>

            @auth
                <div class="flex items-center space-x-3">
                    @if(session('selected_city'))
                        @php $city = \App\Models\City::find(session('selected_city')) @endphp
                        <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm flex items-center">
                        <span class="material-icons text-sm mr-1">location_on</span>
                        {{ $city->name_en ?? 'Город' }}
                    </span>
                    @endif
                    <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-sm">
                    {{ Auth::user()->role === 'driver' ? '🚗 Водитель' : '👤 Пассажир' }}
                </span>
                </div>
            @endauth
        </div>
    </div>
</header>

<!-- Main Content -->
<main class="flex-1 p-4 pb-20">
    @yield('content')
</main>

<!-- Bottom Navigation -->
<nav class="fixed bottom-0 left-0 right-0 bg-white border-t shadow-lg">
    <div class="max-w-md mx-auto flex justify-around py-3">
        <a href="{{ route('home') }}" class="flex flex-col items-center text-gray-600 hover:text-blue-500 transition-colors {{ request()->is('/') ? 'active-tab' : '' }}">
            <span class="material-icons">search</span>
            <span class="text-xs mt-1">Поиск</span>
        </a>

        <a href="{{ route('passenger.search') }}" class="flex flex-col items-center text-gray-600 hover:text-blue-500 transition-colors {{ request()->is('passenger*') ? 'active-tab' : '' }}">
            <span class="material-icons">directions_car</span>
            <span class="text-xs mt-1">Таксисты</span>
        </a>

        <a href="{{ route('trips.index') }}" class="flex flex-col items-center text-gray-600 hover:text-blue-500 transition-colors {{ request()->is('trips*') ? 'active-tab' : '' }}">
            <span class="material-icons">receipt</span>
            <span class="text-xs mt-1">Поездки</span>
        </a>

        @auth
            <a href="{{ route('driver.dashboard') }}" class="flex flex-col items-center text-gray-600 hover:text-blue-500 transition-colors {{ request()->is('driver*') ? 'active-tab' : '' }}">
                <span class="material-icons">dashboard</span>
                <span class="text-xs mt-1">Панель</span>
            </a>

            <a href="{{ route('profile') }}" class="flex flex-col items-center text-gray-600 hover:text-blue-500 transition-colors {{ request()->is('profile*') ? 'active-tab' : '' }}">
                <span class="material-icons">person</span>
                <span class="text-xs mt-1">Профиль</span>
            </a>
        @endauth
    </div>
</nav>

<!-- Material Icons -->
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</body>
</html>

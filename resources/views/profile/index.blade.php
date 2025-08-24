<!-- resources/views/profile/index.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="max-w-md mx-auto">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="w-24 h-24 bg-gradient-to-br from-blue-400 to-blue-600 rounded-2xl mx-auto mb-4 flex items-center justify-center shadow-lg">
                @if(Auth::user()->photo_url)
                    <img src="{{ Auth::user()->photo_url }}" class="w-24 h-24 rounded-2xl object-cover">
                @else
                    <span class="material-icons text-white text-5xl">person</span>
                @endif
            </div>
            <h1 class="text-2xl font-bold text-gray-800 mb-2">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</h1>
            <p class="text-gray-600">@{{ Auth::user()->username }}</p>
        </div>

        <!-- Profile Info Card -->
        <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Информация профиля</h2>

            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-gray-600">Имя:</span>
                    <span class="font-medium">{{ Auth::user()->first_name }}</span>
                </div>

                <div class="flex items-center justify-between">
                    <span class="text-gray-600">Фамилия:</span>
                    <span class="font-medium">{{ Auth::user()->last_name }}</span>
                </div>

                <div class="flex items-center justify-between">
                    <span class="text-gray-600">Username:</span>
                    <span class="font-medium">@{{ Auth::user()->username ?? 'Не указан' }}</span>
                </div>

                <div class="flex items-center justify-between">
                    <span class="text-gray-600">Телефон:</span>
                    <span class="font-medium">{{ Auth::user()->phone ?? 'Не указан' }}</span>
                </div>

                <div class="flex items-center justify-between">
                    <span class="text-gray-600">Роль:</span>
                    <span class="font-medium {{ Auth::user()->role === 'driver' ? 'text-green-600' : 'text-blue-600' }}">
                    {{ Auth::user()->role === 'driver' ? '🚗 Водитель' : '👤 Пассажир' }}
                </span>
                </div>

                @if(Auth::user()->role === 'driver')
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Рейтинг:</span>
                        <span class="font-medium text-yellow-600">⭐ {{ number_format(Auth::user()->rating, 1) }}</span>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Отзывы:</span>
                        <span class="font-medium">{{ Auth::user()->total_ratings }}</span>
                    </div>

                    @if(Auth::user()->car_info)
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">Автомобиль:</span>
                            <span class="font-medium">{{ Auth::user()->car_info }}</span>
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">Госномер:</span>
                            <span class="font-medium">{{ Auth::user()->license_plate }}</span>
                        </div>
                    @endif
                @endif
            </div>

            <div class="mt-6 pt-4 border-t">
                <a href="{{ route('profile.edit') }}" class="w-full bg-blue-500 text-white py-3 rounded-xl font-semibold text-center block hover:bg-blue-600 transition-colors">
                    ✏️ Редактировать профиль
                </a>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Действия</h2>

            <div class="space-y-3">
                <form action="{{ route('profile.switch-role') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full bg-gray-100 text-gray-800 py-3 rounded-xl font-semibold text-center hover:bg-gray-200 transition-colors flex items-center justify-center">
                        <span class="material-icons mr-2">swap_horiz</span>
                        Сменить на {{ Auth::user()->role === 'driver' ? 'пассажира' : 'водителя' }}
                    </button>
                </form>

                <a href="{{ route('trips.index') }}" class="w-full bg-gray-100 text-gray-800 py-3 rounded-xl font-semibold text-center hover:bg-gray-200 transition-colors flex items-center justify-center">
                    <span class="material-icons mr-2">receipt</span>
                    Мои поездки
                </a>

                @if(Auth::user()->role === 'driver')
                    <a href="{{ route('driver.dashboard') }}" class="w-full bg-gray-100 text-gray-800 py-3 rounded-xl font-semibold text-center hover:bg-gray-200 transition-colors flex items-center justify-center">
                        <span class="material-icons mr-2">dashboard</span>
                        Панель водителя
                    </a>
                @else
                    <a href="{{ route('passenger.search') }}" class="w-full bg-gray-100 text-gray-800 py-3 rounded-xl font-semibold text-center hover:bg-gray-200 transition-colors flex items-center justify-center">
                        <span class="material-icons mr-2">search</span>
                        Найти такси
                    </a>
                @endif
            </div>
        </div>

        <!-- Stats -->
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-2xl shadow-lg p-6 text-white mt-6">
            <h3 class="font-semibold text-lg mb-4">Ваша статистика</h3>
            <div class="grid grid-cols-2 gap-4 text-center">
                <div>
                    <div class="text-2xl font-bold">{{ Auth::user()->tripsAsDriver->count() + Auth::user()->tripsAsPassenger->count() }}</div>
                    <div class="text-sm opacity-90">всего поездок</div>
                </div>
                <div>
                    <div class="text-2xl font-bold">{{ Auth::user()->role === 'driver' ? number_format(Auth::user()->rating, 1) . '★' : '—' }}</div>
                    <div class="text-sm opacity-90">рейтинг</div>
                </div>
            </div>
        </div>
    </div>
@endsection

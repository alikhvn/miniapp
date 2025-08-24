<!-- resources/views/profile/edit.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="max-w-md mx-auto">
        <!-- Header -->
        <div class="flex items-center mb-6">
            <a href="{{ route('profile') }}" class="mr-3">
                <span class="material-icons text-gray-600">arrow_back</span>
            </a>
            <h1 class="text-2xl font-bold text-gray-800">Редактирование профиля</h1>
        </div>

        <!-- Edit Form -->
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <form action="{{ route('profile.update') }}" method="POST">
                @csrf

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Имя</label>
                        <input type="text" name="first_name" value="{{ old('first_name', Auth::user()->first_name) }}" required
                               class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-300 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Фамилия</label>
                        <input type="text" name="last_name" value="{{ old('last_name', Auth::user()->last_name) }}" required
                               class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-300 focus:border-transparent">
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Username</label>
                    <input type="text" name="username" value="{{ old('username', Auth::user()->username) }}"
                           class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-300 focus:border-transparent"
                           placeholder="@username">
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Телефон</label>
                    <input type="tel" name="phone" value="{{ old('phone', Auth::user()->phone) }}"
                           class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-300 focus:border-transparent"
                           placeholder="+7 XXX XXX XX XX">
                </div>

                <div class="flex space-x-3">
                    <a href="{{ route('profile') }}" class="flex-1 bg-gray-100 text-gray-800 py-3 rounded-xl font-semibold text-center hover:bg-gray-200 transition-colors">
                        Отмена
                    </a>
                    <button type="submit" class="flex-1 bg-blue-500 text-white py-3 rounded-xl font-semibold hover:bg-blue-600 transition-colors">
                        Сохранить
                    </button>
                </div>
            </form>
        </div>

        <!-- Driver Info (if driver) -->
        @if(Auth::user()->role === 'driver')
            <div class="bg-white rounded-2xl shadow-lg p-6 mt-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Информация водителя</h2>

                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Марка и модель авто</label>
                        <div class="p-3 bg-gray-100 rounded-xl">{{ Auth::user()->car_info }}</div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Госномер</label>
                        <div class="p-3 bg-gray-100 rounded-xl">{{ Auth::user()->license_plate }}</div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
